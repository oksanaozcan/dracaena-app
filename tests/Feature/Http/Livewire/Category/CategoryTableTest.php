<?php

namespace Tests\Feature\Http\Livewire\Category;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Livewire\Livewire;
use Database\Seeders\RoleSeeder;
use Tests\TestHelper;
use App\Http\Livewire\Category\CreateForm;
use App\Http\Livewire\Category\Table;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CategoryTableTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->c = $this->createCategory();
        $this->user = $this->createUserWithRole("admin");
        Livewire::actingAs($this->user);
    }

    public function test_1_it_can_render_the_component()
    {
        $this->get('/categories')->assertSeeLivewire(Table::class);
    }

    public function test_2_it_can_render_the_create_form_component()
    {
        $this->get('/categories')->assertSeeLivewire(CreateForm::class);
    }

    public function test_3_it_can_render_with_categories()
    {
        Livewire::test(Table::class)
            ->assertSee($this->c->title);
    }

    public function test_4_it_can_sort_categories()
    {
        $c1 = Category::factory()->create(['title' => 'bbbbbbbbbb']);
        $c2 = Category::factory()->create(['title' => 'aaaaaaaaaaaa']);

        Livewire::test(Table::class)
            ->call('sortBy', 'title')
            ->assertSeeInOrder([$c2->title, $c1->title]);
    }

    public function test_5_it_can_redirect_to_edit_page_of_model()
    {
        Livewire::test(Table::class, ['id' => $this->c->id])
        ->assertSee($this->c->id)
        ->call("editCategory", $this->c->id)
        ->assertRedirect(route("categories.edit", $this->c->id));
    }

    public function test_6_admin_can_destroy_category_usind_table_component()
    {
        $this->canDestroyCategory();
    }

    public function test_7_manager_can_destroy_category_usind_table_component()
    {
        $manager = $this->createUserWithRole("manager");
        Livewire::actingAs($manager);
        $this->canDestroyCategory();
    }

    public function test_8_assistant_can_not_destroy_category_usind_table_component()
    {
        $assistant = $this->createUserWithRole("assistant");
        Livewire::actingAs($assistant);
        $this->canDestroyCategory(false);
    }

    public function test_9_it_emits_event_category_added_and_calls_render_method()
    {
        Livewire::test(CreateForm::class)
            ->set('title', 'string for test')
            ->set('preview', UploadedFile::fake()->image('test_image.jpg'))
            ->call('submitForm')
            ->assertEmitted('categoryAdded');

        Livewire::test(Table::class)
            ->assertSee('string for test');
    }

    protected function canDestroyCategory(bool $case = true)
    {
        $testId = $this->c->id;
        if ($case) {
            Livewire::test(Table::class)
            ->assertSee($this->c->id)
            ->call("destroyCategory", $this->c->id)
            ->assertEmitted('deletedCategories')
            ->assertDontSee($testId);
            $this->assertDatabaseMissing('categories', ['id' => $testId]);
            } else {
            Livewire::test(Table::class)
            ->assertSee($this->c->id)
            ->call("destroyCategory", $this->c->id)
            ->assertStatus(403);
            $this->assertTrue(Category::whereTitle($this->c->title)->exists());
        }
    }

}
