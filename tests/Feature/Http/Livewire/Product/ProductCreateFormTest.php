<?php

namespace Tests\Feature\Http\Livewire\Product;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Livewire\Livewire;
use Database\Seeders\RoleSeeder;
use Tests\TestHelper;
use App\Http\Livewire\Product\CreateForm;
use App\Http\Livewire\Product\Table;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ProductCreateFormTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->product = $this->createProduct();
        $this->user = $this->createUserWithRole("admin");
        Livewire::actingAs($this->user);
    }

    public function formValidationProvider(): array
    {
        return [
            'title is required' => ['', 'test description', UploadedFile::fake()->image('test_image.jpg'), 25.25, 25, 1],
            'title is string' => [5646548, 'test description', UploadedFile::fake()->image('test_image.jpg'), 25.25, 25, 1],
            // 'title is unique' => [$this->p->title, 'test description', UploadedFile::fake()->image('test_image.jpg'), 25.25, 25, 1],
            'title is min 3 char' => ['vc', 'test description', UploadedFile::fake()->image('test_image.jpg'), 25.25, 25, 1],

            'description is required' => ['test for title', '', UploadedFile::fake()->image('test_image.jpg'), 25.25, 25, 1],
            'description is string' => ['test for title', 252565, UploadedFile::fake()->image('test_image.jpg'), 25.25, 25, 1],
            'description is min 3 char' => ['test for title', 'ff', UploadedFile::fake()->image('test_image.jpg'), 25.25, 25, 1],
            'description is max 100 char' => ['test for title', Str::random(102), UploadedFile::fake()->image('test_image.jpg'), 25.25, 25, 1],

            'preview is required' => ['test for title', 'test for desc', '', 25.25, 25, 1],

            'price is required' => ['test for title', 'test description', UploadedFile::fake()->image('test_image.jpg'), '', 25, 1],
            'price is numeric' => ['test for title', 'test description', UploadedFile::fake()->image('test_image.jpg'), 'string', 25, 1],

            'amount is required' => ['test for title', 'test description', UploadedFile::fake()->image('test_image.jpg'), 25.25, '', 1],
            'amount is numeric' => ['test for title', 'test description', UploadedFile::fake()->image('test_image.jpg'), 25.25, 'string', 1],

            'category_id is required' => ['test for title', 'test description', UploadedFile::fake()->image('test_image.jpg'), 25.25, 25, null],
        ];
    }

    /**
    * @dataProvider formValidationProvider
    */
    public function test_1_it_validates_form($title, $descr, $preview, $price, $amount, $cat_id)
    {
        $res = Livewire::test(CreateForm::class)
            ->set('title', $title)
            ->set('description', $descr)
            ->set('preview', $preview)
            ->set('price', $price)
            ->set('amount', $amount)
            ->set('category_id', $cat_id)
            ->call('submitForm');

        $res->assertHasErrors([
            'title' => $title ? null : 'Required',
            'description' => $descr ? null : 'Required',
            'preview' => $preview ? null : 'Required',
            'price' => $price ? null : 'Required',
            'amount' => $amount ? null : 'Required',
            'category_id' => $cat_id ? null : 'Required',
        ]);
    }

    public function test_2_admin_can_store_model()
    {
       $this->canStoreProduct();
    }

    public function test_3_manager_can_store_model()
    {
        $manager = $this->createUserWithRole("manager");
        Livewire::actingAs($manager);

        $this->canStoreProduct();
    }

    public function test_4_assistant_can_store_model()
    {
        $assistant = $this->createUserWithRole("assistant");
        Livewire::actingAs($assistant);

        $this->canStoreProduct();
    }

    public function test_5_can_set_initial_description()
    {
        Livewire::test(CreateForm::class, ['title' => 'foo'])
            ->assertSet('title', 'foo');
    }

    public function test_6_it_displays_success_message_on_product_create_page_after_creation()
    {
        $this->setFormAndCallMethod()->assertSee('Product successfully added.');
    }

    public function test_7_model_creation_page_contains_livewire_component()
    {
        $this->get('/products/create')->assertSeeLivewire(CreateForm::class);
    }

    public function test_8_model_creation_page_doesnt_contain_livewire_component()
    {
        $this->get('/products/create')->assertDontSeeLivewire(Table::class);
    }

    // //edition of model

    public function test_9_admin_can_edit_title_of_model()
    {
        $this->canEditProduct();
    }

    public function test_10_manager_can_edit_title_of_model()
    {
        $manager = $this->createUserWithRole("manager");
        Livewire::actingAs($manager);

        $this->canEditProduct();
    }

    public function test_11_assistant_can_not_edit_a_model()
    {
        $assistant = $this->createUserWithRole("assistant");
        Livewire::actingAs($assistant);

        $this->canEditProduct(false);
    }

    public function test_12_it_redirects_after_edition_to_index_page()
    {
        $res = Livewire::test(CreateForm::class, ['id' => $this->product->id])
            ->set('title', 'new title')
            ->call('submitForm')
            ->assertRedirect(route("products.index"));
        $res->assertStatus(200);
    }

    public function test_13_model_edition_page_contains_livewire_component()
    {
        $this->get("/products/{$this->product->id}/edit")->assertSeeLivewire(CreateForm::class);
    }

    public function test_14_model_edition_page_doesnt_contain_livewire_component()
    {
        $this->get("/products/{$this->product->id}/edit")->assertDontSeeLivewire(Table::class);
    }

    protected function canEditProduct(bool $case = true)
    {
        if ($case) {
            $res = Livewire::test(CreateForm::class, ['id' => $this->product->id])
            ->set('title', 'test of editing')
            ->call('submitForm');

            $res->assertStatus(200);

            $this->assertTrue(Product::whereTitle('test of editing')->exists());
        } else {
            $res = Livewire::test(CreateForm::class, ['id' => $this->product->id])
            ->set('title', 'test of editing')
            ->call('submitForm');

            $res->assertStatus(403);

            $this->assertFalse(Product::whereTitle('test of editing')->exists());
        }
    }

    protected function canStoreProduct(bool $case = true)
    {
        if ($case) {
            $res = $this->setFormAndCallMethod();
            $res->assertStatus(200);
            $this->assertTrue(Product::whereTitle('test title 45644548754')->exists());
        } else {
            $res = $this->setFormAndCallMethod();
            $res->assertStatus(403);
            $this->assertFalse(Product::whereTitle('test title 45644548754')->exists());
        }
    }

    protected function setFormAndCallMethod()
    {
        $res = Livewire::test(CreateForm::class)
            ->set('title', 'test title 45644548754')
            ->set('description', 'test descr')
            ->set('preview', UploadedFile::fake()->image('test_image.jpg'))
            ->set('price', 25.25)
            ->set('amount', 25)
            ->set('category_id', 1)
            ->call('submitForm');
        return $res;
    }
}
