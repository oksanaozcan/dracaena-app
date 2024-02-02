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

class CategoryCreateFormTest extends TestCase
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

    public function formValidationProvider(): array
    {
        return [
            'title is required' => ['', UploadedFile::fake()->image('test_image.jpg')],
            'title is string' => [556455, UploadedFile::fake()->image('test_image.jpg')],
            'title is unique' => ['houseplants', UploadedFile::fake()->image('test_image.jpg')],
            'title is min 3 char' => ['vs', UploadedFile::fake()->image('test_image.jpg')],
            'preview is required' => ['test title', ''],
        ];
    }

    /**
    * @dataProvider formValidationProvider
    */
    public function test_1_it_validates_form($a, $b)
    {
        Livewire::test(CreateForm::class)
            ->set('title', $a)
            ->set('preview', $b)
            ->call('submitForm')
            ->assertHasErrors([
            'title' => $a ? null : 'Required',
            'preview' => $b ? null : 'Required',
        ]);
    }

    public function test_2_admin_can_store_model()
    {
       $this->canStoreCategory();
    }

    public function test_3_manager_can_store_model()
    {
        $manager = $this->createUserWithRole("manager");
        Livewire::actingAs($manager);

        $this->canStoreCategory();
    }

    public function test_4_assistant_can_not_store_model()
    {
        $assistant = $this->createUserWithRole("assistant");
        Livewire::actingAs($assistant);

        $this->canStoreCategory(false);
    }

    public function test_5_can_set_initial_description()
    {
        Livewire::test(CreateForm::class, ['title' => 'foo'])
            ->assertSet('title', 'foo');
    }

    public function test_6_it_displays_success_message_on_category_create_page_after_creation()
    {
        $this->setFormAndCallMethod()->assertSee('Category successfully added.');
    }

    public function test_7_model_creation_page_contains_livewire_component()
    {
        $this->get('/categories/create')->assertSeeLivewire(CreateForm::class);
    }

    public function test_8_model_creation_page_doesnt_contain_livewire_component()
    {
        $this->get('/categories/create')->assertDontSeeLivewire(Table::class);
    }

    //edition of model

    public function test_9_admin_can_edit_title_of_model()
    {
        $this->canEditCategory();
    }

    public function test_10_manager_can_edit_title_of_model()
    {
        $manager = $this->createUserWithRole("manager");
        Livewire::actingAs($manager);

        $this->canEditCategory();
    }

    public function test_11_assistant_can_not_edit_a_model()
    {
        $assistant = $this->createUserWithRole("assistant");
        Livewire::actingAs($assistant);

        $this->canEditCategory(false);
    }

    public function test_12_it_redirects_after_edition_to_index_page()
    {
        $res = Livewire::test(CreateForm::class, ['id' => $this->c->id])
            ->set('title', 'new title')
            ->call('submitForm')
            ->assertRedirect(route('categories.index'));
        $res->assertStatus(200);
    }

    public function test_13_model_edition_page_contains_livewire_component()
    {
        $this->get("/categories/{$this->c->id}/edit")->assertSeeLivewire(CreateForm::class);
    }

    public function test_14_model_edition_page_doesnt_contain_livewire_component()
    {
        $this->get("/categories/{$this->c->id}/edit")->assertDontSeeLivewire(Table::class);
    }

    protected function canEditCategory(bool $case = true)
    {
        if ($case) {
            $res = Livewire::test(CreateForm::class, ['id' => $this->c->id])
            ->set('title', 'test of editing')
            ->call('submitForm');

            $res->assertStatus(200);

            $this->assertTrue(Category::whereTitle('test of editing')->exists());
        } else {
            $res = Livewire::test(CreateForm::class, ['id' => $this->c->id])
            ->set('title', 'test of editing')
            ->call('submitForm');

            $res->assertStatus(403);

            $this->assertFalse(Category::whereTitle('test of editing')->exists());
        }
    }

    protected function canStoreCategory(bool $case = true)
    {
        if ($case) {
            $res = $this->setFormAndCallMethod();
            $res->assertStatus(200);
            $this->assertTrue(Category::whereTitle('string for testing')->exists());
        } else {
            $res = $this->setFormAndCallMethod();
            $res->assertStatus(403);
            $this->assertFalse(Category::whereTitle('string for testing')->exists());
        }
    }

    protected function setFormAndCallMethod()
    {
        $res = Livewire::test(CreateForm::class)
        ->set('title', 'string for testing')
        ->set('preview', UploadedFile::fake()->image('test_image.jpg'))
        ->call('submitForm');
        return $res;
    }

}
