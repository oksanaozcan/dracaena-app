<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Cart;
use App\Models\Order;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_1_can_create_product()
    {
        $product = Product::factory()->create();

        $this->assertInstanceOf(Product::class, $product);
    }

    public function test_2_product_belongs_to_category()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals($category->id, $product->category->id);
    }

    public function test_3_product_can_have_tags()
    {
        $product = Product::factory()->create();
        $tag1 = Tag::factory()->create();
        $tag2 = Tag::factory()->create();

        $product->tags()->attach([$tag1->id, $tag2->id]);

        $this->assertInstanceOf(Tag::class, $product->tags->first());
        $this->assertEquals(2, $product->tags->count());
    }

    public function test_4_product_can_have_carts()
    {
        $product = Product::factory()->create();
        $cart = Cart::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Cart::class, $product->carts->first());
        $this->assertEquals($cart->id, $product->carts->first()->id);
    }

    public function test_5_product_can_have_orders()
    {
        $product = Product::factory()->create();
        $order = Order::factory()->create();

        $product->orders()->attach($order->id);

        $this->assertInstanceOf(Order::class, $product->orders->first());
        $this->assertEquals($order->id, $product->orders->first()->id);
    }
}
