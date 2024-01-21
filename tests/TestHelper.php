<?php

namespace Tests;

use App\Models\Category;
use App\Models\CategoryFilter;
use App\Models\Product;
use App\Models\Client;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Cart;
use App\Models\User;
use App\Models\Billboard;
use App\Types\RoleType;
use Illuminate\Support\Str;

trait TestHelper
{
    use WithFaker;

    protected function createCategory(): Category
    {
        $category = Category::factory()->create();
        return $category;
    }

    protected function createCategoryFilter(): CategoryFilter
    {
        $cat = Category::factory()->create();
        $cf = CategoryFilter::factory()->create([
            'category_id' => $cat->id,
        ]);
        return $cf;
    }

    protected function createCategoryAndProduct(): array
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'title' => $this->faker->word() . 'Product',
            'description' => $this->faker->sentence,
            'content' => $this->faker->text,
            'preview' => $this->faker->imageUrl(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'amount' => $this->faker->randomNumber(4),
            'category_id' => $category->id,
        ]);

        return ['category' => $category, 'product' => $product];
    }

    protected function createClient(): Client
    {
        $client = Client::factory()->create();
        return $client;
    }

    protected function createProductAndPutItInCartOfClient(): array
    {
        $client = $this->createClient();
        $data = $this->createCategoryAndProduct();
        $pr = $data['product'];

        $cartItem = Cart::create([
            'client_id' => $client->clerk_id,
            'product_id' => $pr->id,
        ]);

        return ['product' => $pr, 'client' => $client, 'cartItem' => $cartItem];
    }

    protected function createUser(): User
    {
        $user = User::create([
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'email_verified_at' => now(),
            'password' => bcrypt('123456789'),
            'remember_token' => Str::random(10),
        ])->assignRole(RoleType::ASSISTANT);

        return $user;
    }

    protected function createBillboard(): Billboard
    {
        $category = Category::factory()->create();

        $b = Billboard::create([
            'image' => $this->faker->imageUrl(),
            'category_id' => $category->id,
            'description' => $this->faker->sentence,
        ]);

        return $b;
    }

    protected function assertRoleCanAccessPage($roleType, $route, $model=null, $page)
    {
        $user = User::factory()->$roleType()->create();
        $response = $this->actingAs($user)->get(route($route));

        if ($model == null) {
            $response->assertStatus(200)->assertViewIs($page);
        } else {
            $response->assertStatus(200)->assertViewIs("$model.$page");
        }
    }

    protected function assertRoleCanDeleteModel($roleType, $model, $route, $dir, $tableName=null)
    {
        $user = User::factory()->$roleType()->create();

        $response = $this->actingAs($user)
            ->delete(route($route, $model));
        $response->assertRedirect(route("$dir.index"));

        if ($tableName == null) {
            $this->assertDatabaseMissing($dir, ['id' => $model->id]);
        } else {
            $this->assertDatabaseMissing($tableName, ['id' => $model->id]);
        }





    }

    protected function assertRoleCanNotDeleteModel($roleType, $model, $route)
    {

        $user = User::factory()->$roleType()->create();

        $response = $this->actingAs($user)
            ->delete(route($route, $model));

        $response->assertStatus(403);
    }

    protected function assertRoleCanEditModel($roleType, $model, $route, $view)
    {
        $user = User::factory()->$roleType()->create();

        $response = $this->actingAs($user)
            ->get(route($route, $model));

        $response->assertStatus(200)
            ->assertViewIs($view)
            ->assertViewHas('id', $model->id);
    }

    protected function assertRoleCanNotEditModel($roleType, $model, $route)
    {
        $user = User::factory()->$roleType()->create();

        $response = $this->actingAs($user)
            ->get(route($route, $model));

        $response->assertStatus(403);
    }

    protected function assertRoleCanAccessShowPage($roleType, $model, $route, $view)
    {
        $user = User::factory()->$roleType()->create();
        $response = $this->actingAs($user)
            ->get(route($route, $model->id));

        $response->assertStatus(200)
            ->assertViewIs($view);
    }

    protected function assertRedirectNotAuthUsersFromPageToVerifNoticeRoute($route, $model=null)
    {
        if ($model != null) {
            $response = $this->get(route($route, $model->id));
        } else {
            $response = $this->get(route($route));
        }
        $response->assertStatus(302)
            ->assertRedirect(route('verification.notice'));
    }

    protected function assertRedirectNotAuthUsersToLogin($route, $model=null)
    {
        if ($model != null) {
            $response = $this->get(route($route, $model->id));
        } else {
            $response = $this->get(route($route));
        }
        $response->assertStatus(302)
            ->assertRedirect(route('login'));
    }
}
