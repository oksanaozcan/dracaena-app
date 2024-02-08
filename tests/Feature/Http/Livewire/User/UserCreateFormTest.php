<?php

namespace Tests\Feature\Http\Livewire\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Livewire\Livewire;
use Database\Seeders\RoleSeeder;
use Tests\TestHelper;
use App\Http\Livewire\User\CreateForm;
use App\Http\Livewire\User\Table;
use App\Models\User;
use App\Mail\User\PasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Queue;

class UserCreateFormTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->assistant = $this->createUserWithRole("assistant");
        $this->manager = $this->createUserWithRole("manager");
        $this->admin = $this->createUserWithRole("admin");
        Livewire::actingAs($this->admin);
    }

    public function formValidationProvider(): array
    {
        return [
            'name is required' => ['', 'testuser@gmail.com', 3],
            'name is string' => [54564554, 'testuser@gmail.com', 3],
            'name is min 3 char' => ['dd', 'testuser@gmail.com', 3],

            'email is required' => ['test name', '', 3],
            'email is string' => ['test name', 554445454, 3],
            'email is email' => ['test name', 'somestringnotmail', 3],
            'email is unique' => ['test name', 'admin@gmail.com', 3],

            'roleName is requited' => ['test name', 'testuser@gmail.com', null],
        ];
    }

    /**
    * @dataProvider formValidationProvider
    */
    public function test_1_it_validates_form($name, $email, $role)
    {
        $res = Livewire::test(CreateForm::class)
            ->set('name', $name)
            ->set('email', $email)
            ->set('roleName', $role)
            ->call('submitForm');

        $res->assertHasErrors([
            'name' => $name ? null : 'Required',
            'email' => $email ? null : 'Required',
            'roleName' => $role ? null : 'Required',
        ]);
    }

    public function test_2_admin_can_store_model()
    {
       $this->canStoreUser();
    }

    public function test_3_manager_can_not_store_model()
    {
        Livewire::actingAs($this->manager);
        $this->canStoreUser(false);
    }

    public function test_4_assistant_can_not_store_model()
    {
        Livewire::actingAs($this->assistant);
        $this->canStoreUser(false);
    }

    public function test_5_can_set_initial_description()
    {
        Livewire::test(CreateForm::class, ['name' => 'oksana ozcan'])
            ->assertSet('name', 'oksana ozcan');
    }

    public function test_6_it_displays_success_message_on_user_create_page_after_creation()
    {
        $this->setFormAndCallMethod()->assertSee('User successfully added.');
    }

    public function test_7_model_creation_page_contains_livewire_component()
    {
        $this->get('/users/create')->assertSeeLivewire(CreateForm::class);
    }

    public function test_8_model_creation_page_doesnt_contain_livewire_component()
    {
        $this->get('/users/create')->assertDontSeeLivewire(Table::class);
    }

    //edition of model

    public function test_9_admin_can_edit_name_of_model()
    {
        $this->canEditUser();
    }

    public function test_10_manager_can_not_edit_a_model()
    {
        Livewire::actingAs($this->manager);
        $this->canEditUser(false);
    }

    public function test_11_assistant_can_not_edit_a_model()
    {
        Livewire::actingAs($this->assistant);
        $this->canEditUser(false);
    }

    public function test_12_it_redirects_after_edition_to_index_page()
    {
        $res = Livewire::test(CreateForm::class, ['id' => $this->assistant->id])
            ->set('name', 'new name')
            ->call('submitForm')
            ->assertRedirect(route("users.index"));
        $res->assertStatus(200);
    }

    public function test_13_model_edition_page_contains_livewire_component()
    {
        $this->get("/users/{$this->assistant->id}/edit")->assertSeeLivewire(CreateForm::class);
    }

    public function test_14_model_edition_page_doesnt_contain_livewire_component()
    {
        $this->get("/users/{$this->assistant->id}/edit")->assertDontSeeLivewire(Table::class);
    }

    public function test_14_it_sends_email_after_storing_user()
    {
        Mail::fake();

        $testUser = $this->createUserWithRole("assistant");

        Mail::to($testUser)->send(new PasswordMail($testUser->password));

        Mail::assertQueued(PasswordMail::class);
    }

    protected function canEditUser(bool $case = true)
    {
        if ($case) {
            $res = Livewire::test(CreateForm::class, ['id' => $this->assistant->id])
            ->set('name', 'test of editing')
            ->call('submitForm');

            $res->assertStatus(200);

            $this->assertTrue(User::whereName('test of editing')->exists());
        } else {
            $res = Livewire::test(CreateForm::class, ['id' => $this->assistant->id])
            ->set('name', 'test of editing')
            ->call('submitForm');

            $res->assertStatus(403);

            $this->assertFalse(User::whereName('test of editing')->exists());
        }
    }

    protected function canStoreUser(bool $case = true)
    {
        if ($case) {
            $res = $this->setFormAndCallMethod();
            $res->assertStatus(200);
            $this->assertTrue(User::whereName('test name')->exists());
        } else {
            $res = $this->setFormAndCallMethod();
            $res->assertStatus(403);
            $this->assertFalse(User::whereName('test name')->exists());
        }
    }

    protected function setFormAndCallMethod()
    {
        $res = Livewire::test(CreateForm::class)
            ->set('name', 'test name')
            ->set('email', 'testname@gmail.com')
            ->set('roleName', 3)
            ->call('submitForm');
        return $res;
    }
}
