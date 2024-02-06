<?php

namespace Tests\Feature\Http\Livewire\CategoryFilter;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Livewire\Livewire;
use Database\Seeders\RoleSeeder;
use Tests\TestHelper;
use App\Http\Livewire\CategoryFilter\CreateForm;
use App\Http\Livewire\CategoryFilter\Table;
use App\Models\CategoryFilter;

class CategoryFilterTableTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->cf = $this->createCategoryFilter();
        $this->user = $this->createUserWithRole("admin");
        Livewire::actingAs($this->user);
    }

    public function test_1_it_can_render_the_component()
    {
        $this->get('/category-filters')->assertSeeLivewire(Table::class);
    }

    public function test_2_it_can_render_the_create_form_component()
    {
        $this->get('/category-filters')->assertSeeLivewire(CreateForm::class);
    }

    public function test_3_it_can_render_with_category_filters()
    {
        Livewire::test(Table::class)
            ->assertSee($this->cf->title);
    }

    public function test_4_it_can_sort_category_filters()
    {
        $cf1 = CategoryFilter::factory()->create(['title' => 'bbbbbbbbbb']);
        $cf2 = CategoryFilter::factory()->create(['title' => 'aaaaaaaaaaaa']);

        Livewire::test(Table::class)
            ->call('sortBy', 'title')
            ->assertSeeInOrder([$cf2->title, $cf1->title]);
    }

    public function test_5_it_can_redirect_to_edit_page_of_model()
    {
        Livewire::test(Table::class, ['id' => $this->cf->id])
        ->assertSee($this->cf->id)
        ->call("editCategoryFilter", $this->cf->id)
        ->assertRedirect(route("category-filters.edit", $this->cf->id));
    }

    public function test_6_admin_can_destroy_category_filter_usind_table_component_if_this_one_has_not_any_tags_or_its_tags_have_not_any_products()
    {
        $this->canDestroyCategoryFilter();
    }

    public function test_7_manager_can_destroy_category_filter_usind_table_component_if_this_one_has_not_any_tags_or_its_tags_have_not_any_products()
    {
        $manager = $this->createUserWithRole("manager");
        Livewire::actingAs($manager);
        $this->canDestroyCategoryFilter();
    }

    public function test_8_assistant_can_not_destroy_category_filter_usind_table_component()
    {
        $assistant = $this->createUserWithRole("assistant");
        Livewire::actingAs($assistant);
        $this->canDestroyCategoryFilter(false);
    }

    public function test_9_user_can_not_destroy_category_filter_if_this_one_has_a_relationships_with_any_tags_that_has_any_products()
    {
       $data = $this->createCategoryAndProduct();
       $data1 = $this->createCategoryFilterWithTag();

       $p = $data['product'];
       $cf = $data1['category_filter'];
       $tag = $data1['tag'];

       $p->tags()->attach([$tag->id]);

       Livewire::test(Table::class)
       ->assertSee($cf->id)
       ->assertSee($cf->title)
       ->call("destroyCategoryFilter", $cf->id)
       ->assertRedirect(route("category-filters.show", $cf))
       ->assertStatus(200);
       $this->assertTrue(CategoryFilter::whereId($cf->id)->exists());
    }

    public function test_10_it_emits_event_category_filter_added_and_calls_render_method()
    {
        $cat = $this->createCategory();

        Livewire::test(CreateForm::class)
            ->set('title', 'string for test')
            ->set('category_id', $cat->id)
            ->call('submitForm')
            ->assertEmitted('categoryFilterAdded');

        Livewire::test(Table::class)
            ->assertSee('string for test');
    }

    protected function canDestroyCategoryFilter(bool $case = true)
    {
        $testId = $this->cf->id;
        if ($case) {
            Livewire::test(Table::class)
            ->assertSee($this->cf->id)
            ->call("destroyCategoryFilter", $this->cf->id)
            ->assertEmitted('deletedCategoryFilters');
            $this->assertDatabaseMissing('category_filters', ['id' => $testId]);
            } else {
            Livewire::test(Table::class)
            ->assertSee($this->cf->id)
            ->call("destroyCategoryFilter", $this->cf->id)
            ->assertStatus(403);
            $this->assertTrue(CategoryFilter::whereTitle($this->cf->title)->exists());
        }
    }

}
