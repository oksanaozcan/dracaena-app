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

class ProductTableTest extends TestCase
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

    public function test_1_it_can_render_the_component()
    {
        $this->get('/products')->assertSeeLivewire(Table::class);
    }

    public function test_2_it_can_not_render_the_create_form_component()
    {
        $this->get('/products')->assertDontSeeLivewire(CreateForm::class);
    }

    public function test_3_it_can_render_with_product()
    {
        Livewire::test(Table::class)
            ->assertSee($this->product->title);
    }

    public function test_4_it_can_sort_products()
    {
        $p1 = Product::factory()->create(['title' => 'bbbbbbbbbb']);
        $p2 = Product::factory()->create(['title' => 'aaaaaaaaaaaa']);

        Livewire::test(Table::class)
            ->call('sortBy', 'title')
            ->assertSeeInOrder([$p2->title, $p1->title]);
    }

    public function test_5_it_can_redirect_to_edit_page_of_model()
    {
        Livewire::test(Table::class, ['id' => $this->product->id])
        ->assertSee($this->product->id)
        ->call("editProduct", $this->product->id)
        ->assertRedirect(route("products.edit", $this->product->id));
    }

    public function test_6_admin_can_destroy_product_usind_table_component()
    {
        $this->canDestroyProduct();
    }

    public function test_7_manager_can_destroy_product_usind_table_component()
    {
        $manager = $this->createUserWithRole("manager");
        Livewire::actingAs($manager);
        $this->canDestroyProduct();
    }

    public function test_8_assistant_can_not_destroy_product_usind_table_component()
    {
        $assistant = $this->createUserWithRole("assistant");
        Livewire::actingAs($assistant);
        $this->canDestroyProduct(false);
    }

    public function test_9_it_emits_event_product_added_and_calls_render_method()
    {
        $cat = $this->createCategory();

        Livewire::test(CreateForm::class)
        ->set('title', 'test title 5646844678416847310')
        ->set('description', 'test descr')
        ->set('preview', UploadedFile::fake()->image('test_image.jpg'))
        ->set('price', 25.25)
        ->set('amount', 25)
        ->set('category_id', $cat->id)
        ->call('submitForm')
        ->assertEmitted('productAdded');

        Livewire::test(Table::class)
            ->assertSee('test title 5646844678416847310');
    }

    public function test_10_it_can_search_for_products()
    {
        $p1 = Product::factory()->create(['title' => 'Product 1']);
        $p2 = Product::factory()->create(['title' => 'Product 2']);

        Livewire::test(Table::class)
            ->set('search', 'Product 1')
            ->assertSee($p1->title)
            ->assertDontSee($p2->title);
    }

    protected function canDestroyProduct(bool $case = true)
    {
        $testId = $this->product->id;
        if ($case) {
            Livewire::test(Table::class)
            ->assertSee($this->product->id)
            ->call("destroyProduct", $this->product->id)
            ->assertEmitted('deletedProducts');
            $this->assertDatabaseMissing('products', ['id' => $testId]);
            } else {
            Livewire::test(Table::class)
            ->assertSee($this->product->id)
            ->call("destroyProduct", $this->product->id)
            ->assertStatus(403);
            $this->assertTrue(Product::whereId($this->product->id)->exists());
        }
    }

}
