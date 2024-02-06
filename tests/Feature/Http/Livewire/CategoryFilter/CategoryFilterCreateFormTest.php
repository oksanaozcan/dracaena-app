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

class CategoryFilterCreateFormTest extends TestCase
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

    public function formValidationProvider(): array
    {
        return [
            'title is required' => ['', 1],
            'title is string' => [556455, 1],
            'title is min 3 char' => ['vs', 1],
            'category_id is required' => ['test title', ''],
        ];
    }

    /**
    * @dataProvider formValidationProvider
    */
    public function test_1_it_validates_form($a, $b)
    {
        Livewire::test(CreateForm::class)
            ->set('title', $a)
            ->set('category_id', $b)
            ->call('submitForm')
            ->assertHasErrors([
            'title' => $a ? null : 'Required',
            'category_id' => $b ? null : 'Required',
        ]);
    }

    public function test_2_admin_can_store_model()
    {
       $this->canStoreCategoryFilter();
    }

    public function test_3_manager_can_store_model()
    {
        $manager = $this->createUserWithRole("manager");
        Livewire::actingAs($manager);

        $this->canStoreCategoryFilter();
    }

    public function test_4_assistant_can_not_store_model()
    {
        $assistant = $this->createUserWithRole("assistant");
        Livewire::actingAs($assistant);

        $this->canStoreCategoryFilter(false);
    }

    public function test_5_can_set_initial_description()
    {
        Livewire::test(CreateForm::class, ['title' => 'foo'])
            ->assertSet('title', 'foo');
    }

    public function test_6_it_displays_success_message_on_category_filter_create_page_after_creation()
    {
        $this->setFormAndCallMethod()->assertSee('Category Filter successfully added.');
    }

    public function test_7_model_creation_page_contains_livewire_component()
    {
        $this->get('/category-filters/create')->assertSeeLivewire(CreateForm::class);
    }

    public function test_8_model_creation_page_doesnt_contain_livewire_component()
    {
        $this->get('/category-filters/create')->assertDontSeeLivewire(Table::class);
    }

    //edition of model

    public function test_9_admin_can_edit_title_of_model()
    {
        $this->canEditCategoryFilter();
    }

    public function test_10_manager_can_edit_title_of_model()
    {
        $manager = $this->createUserWithRole("manager");
        Livewire::actingAs($manager);

        $this->canEditCategoryFilter();
    }

    public function test_11_assistant_can_not_edit_a_model()
    {
        $assistant = $this->createUserWithRole("assistant");
        Livewire::actingAs($assistant);

        $this->canEditCategoryFilter(false);
    }

    public function test_12_it_redirects_after_edition_to_index_page()
    {
        $res = Livewire::test(CreateForm::class, ['id' => $this->cf->id])
            ->set('title', 'new title')
            ->call('submitForm')
            ->assertRedirect(route("category-filters.index"));
        $res->assertStatus(200);
    }

    public function test_13_model_edition_page_contains_livewire_component()
    {
        $this->get("/category-filters/{$this->cf->id}/edit")->assertSeeLivewire(CreateForm::class);
    }

    public function test_14_model_edition_page_doesnt_contain_livewire_component()
    {
        $this->get("/category-filters/{$this->cf->id}/edit")->assertDontSeeLivewire(Table::class);
    }

    protected function canEditCategoryFilter(bool $case = true)
    {
        if ($case) {
            $res = Livewire::test(CreateForm::class, ['id' => $this->cf->id])
            ->set('title', 'test of editing')
            ->call('submitForm');

            $res->assertStatus(200);

            $this->assertTrue(CategoryFilter::whereTitle('test of editing')->exists());
        } else {
            $res = Livewire::test(CreateForm::class, ['id' => $this->cf->id])
            ->set('title', 'test of editing')
            ->call('submitForm');

            $res->assertStatus(403);

            $this->assertFalse(CategoryFilter::whereTitle('test of editing')->exists());
        }
    }

    protected function canStoreCategoryFilter(bool $case = true)
    {
        if ($case) {
            $res = $this->setFormAndCallMethod();
            $res->assertStatus(200);
            $this->assertTrue(CategoryFilter::whereTitle('string for testing')->exists());
        } else {
            $res = $this->setFormAndCallMethod();
            $res->assertStatus(403);
            $this->assertFalse(CategoryFilter::whereTitle('string for testing')->exists());
        }
    }

    protected function setFormAndCallMethod()
    {
        $res = Livewire::test(CreateForm::class)
        ->set('title', 'string for testing')
        ->set('category_id', 1)
        ->call('submitForm');
        return $res;
    }

}
