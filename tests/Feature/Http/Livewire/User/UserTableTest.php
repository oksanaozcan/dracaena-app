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

class UserTableTest extends TestCase
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

    public function test_1_it_can_render_the_component()
    {
        $this->get('/users')->assertSeeLivewire(Table::class);
    }

    public function test_2_it_can_not_render_the_create_form_component()
    {
        $this->get('/users')->assertDontSeeLivewire(CreateForm::class);
    }

    public function test_3_it_can_render_with_model()
    {
        Livewire::test(Table::class)
            ->assertSee($this->admin->name);
    }

    public function test_4_it_can_sort_users()
    {
        $u1 = User::factory()->create(['name' => 'bbbbbbbbbb']);
        $u2 = User::factory()->create(['name' => 'aaaaaaaaaaaa']);

        Livewire::test(Table::class)
            ->call('sortBy', 'name')
            ->assertSeeInOrder([$u2->name, $u1->name]);
    }

    public function test_5_it_can_redirect_to_edit_page_of_model()
    {
        Livewire::test(Table::class, ['id' => $this->assistant->id])
        ->assertSee($this->assistant->id)
        ->call("editUser", $this->assistant->id)
        ->assertRedirect(route("users.edit", $this->assistant->id));
    }

    public function test_6_admin_can_destroy_user_usind_table_component()
    {
        $this->canDestroyUser();
    }

    public function test_7_manager_can_not_destroy_another_user_usind_table_component()
    {
        Livewire::actingAs($this->manager);
        $this->canDestroyUser(false);
    }

    public function test_8_assistant_can_not_destroy_another_user_usind_table_component()
    {
        Livewire::actingAs($this->assistant);
        $this->canDestroyUser(false);
    }

    public function test_9_it_emits_event_user_added_and_calls_render_method()
    {
        Livewire::test(CreateForm::class)
        ->set('name', 'test name')
        ->set('email', 'test@gmail.com')
        ->set('roleName', 3)
        ->call('submitForm')
        ->assertEmitted('userAdded');

        Livewire::test(Table::class)
            ->assertSee('test name');
    }

    protected function canDestroyUser(bool $case = true)
    {
        $testId = $this->assistant->id;
        if ($case) {
            Livewire::test(Table::class)
            ->assertSee($this->assistant->id)
            ->call("destroyUser", $this->assistant->id)
            ->assertEmitted('deletedUsers');
            $this->assertDatabaseMissing('users', ['id' => $testId]);
            } else {
            Livewire::test(Table::class)
            ->assertSee($this->assistant->id)
            ->call("destroyUser", $this->assistant->id)
            ->assertStatus(403);
            $this->assertTrue(User::whereId($this->assistant->id)->exists());
        }
    }

}
