<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Livewire\Livewire;
use Database\Seeders\RoleSeeder;
use Tests\TestHelper;
use App\Http\Livewire\Billboard\CreateForm;
use App\Http\Livewire\Billboard\Table;
use App\Models\Billboard;
use Illuminate\Http\UploadedFile;

class LivewireBuilboardCreateFormTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_1_can_create_model()
    {
        $user = $this->createUserWithRole("admin");
        $cat = $this->createCategory();
        Livewire::actingAs($user);

        Livewire::test(CreateForm::class)
            ->set('description', 'string for testing livewire form for billboard')
            ->set('image', UploadedFile::fake()->image('test_image.jpg'))
            ->set('category_id', $cat->id)
            ->call('submitForm');

        $this->assertTrue(Billboard::whereDescription('string for testing livewire form for billboard')->exists());
    }

    public function test_2_can_set_initial_description()
    {
        $user = $this->createUserWithRole("admin");
        Livewire::actingAs($user);

        Livewire::test(CreateForm::class, ['description' => 'foo'])
            ->assertSet('description', 'foo');
    }

    public function test_3_description_is_required()
    {
        $cat = $this->createCategory();
        $user = $this->createUserWithRole("admin");
        Livewire::actingAs($user);

        Livewire::test(CreateForm::class)
            ->set('description', '')
            ->set('image', UploadedFile::fake()->image('test_image.jpg'))
            ->set('category_id', $cat->id)
            ->call('submitForm')
            ->assertHasErrors(['description' => 'required']);
    }

    public function test_4_image_is_required()
    {
        $cat = $this->createCategory();
        $user = $this->createUserWithRole("admin");
        Livewire::actingAs($user);

        Livewire::test(CreateForm::class)
            ->set('description', 'string for testing livewire form for billboard')
            ->set('image', '')
            ->set('category_id', $cat->id)
            ->call('submitForm')
            ->assertHasErrors(['image' => 'required']);
    }

    public function test_5_category_id_is_required()
    {
        $user = $this->createUserWithRole("admin");
        Livewire::actingAs($user);

        Livewire::test(CreateForm::class)
            ->set('description', 'string for testing livewire form for billboard')
            ->set('image', UploadedFile::fake()->image('test_image.jpg'))
            ->set('category_id', '')
            ->call('submitForm')
            ->assertHasErrors(['category_id' => 'required']);
    }

    public function test_6_it_displays_success_message_on_billboard_create_page_after_creation()
    {
        $user = $this->createUserWithRole("admin");
        $cat = $this->createCategory();
        Livewire::actingAs($user);

        Livewire::test(CreateForm::class)
            ->set('description', 'string for testing livewire form for billboard')
            ->set('image', UploadedFile::fake()->image('test_image.jpg'))
            ->set('category_id', $cat->id)
            ->call('submitForm')
            ->assertSee('Billboard successfully added.');
    }

    public function test_7_model_creation_page_contains_livewire_component()
    {
        $user = $this->createUserWithRole("admin");
        Livewire::actingAs($user);

        $this->get('/billboards/create')->assertSeeLivewire(CreateForm::class);
    }

    public function test_8_model_creation_page_doesnt_contain_livewire_component()
    {
        $user = $this->createUserWithRole("admin");
        Livewire::actingAs($user);

        $this->get('/billboards/create')->assertDontSeeLivewire(Table::class);
    }

    //edition of model

    public function test_9_can_edit_description_of_model()
    {
        $b = $this->createBillboard();
        $user = $this->createUserWithRole("admin");

        Livewire::actingAs($user)
            ->test(CreateForm::class, ['id' => $b->id])
            ->set('description', 'test of editing')
            ->call('submitForm');

        $this->assertTrue(Billboard::whereDescription('test of editing')->exists());
    }

    public function test_10_can_edit_image_of_model()
    {
        $b = $this->createBillboard();
        $user = $this->createUserWithRole("admin");

        Livewire::actingAs($user)
            ->test(CreateForm::class, ['id' => $b->id])
            ->set('image', UploadedFile::fake()->image('test_image.jpg'))
            ->call('submitForm');

            dd($b);

        $this->assertTrue(Billboard::whereDescription($b->description)->exists());

        $this->assertTrue(Billboard::where('image', 'http://localhost/storage/billboard_images/test_image.jpg')->exists());
    }

}
