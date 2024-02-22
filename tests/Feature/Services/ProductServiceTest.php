<?php

namespace Tests\Feature\Services;

use App\Services\ProductService;
use App\Models\Product;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Tests\TestHelper;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->p = $this->createProduct();
        $this->ps = new ProductService();
    }

    public function test_it_catches_exception_of_storing_product_and_rolls_back_transaction()
    {
        $title = 'Test Product';
        $descr = 'Test descr Product';
        $price = 25.25;
        $amount = 25;
        $category_id = 1;

        Storage::fake('public');
        $preview = UploadedFile::fake()->image('preview.jpg');

        $this->dbBeginRollback();
        Storage::shouldReceive('disk->put')->andReturnUsing(function () {
            throw new Exception('Failed to store preview image');
        });

        try {
            $this->ps->storeProduct($title, $preview, $descr, null, $price, $amount, $category_id, []);
        } catch (Exception $exception) {
            $this->assertEquals(500, $exception->getStatusCode());
            return;
        }

        $this->fail('Exception was not caught.');
    }

    public function test_it_catches_exception_of_updating_product_and_rolls_back_transaction()
    {
        $title = 'Test Product';
        $descr = 'Test descr Product';
        $price = 25.25;
        $amount = 25;
        $category_id = 1;

        Storage::fake('public');
        $preview = UploadedFile::fake()->image('preview.jpg');

        $this->dbBeginRollback();
        Storage::shouldReceive('disk->put')->andReturnUsing(function () {
            throw new Exception('Failed to store preview image');
        });

        try {
            $this->ps->updateProduct($title, $this->p, $preview, $descr, null, $price, $amount, $category_id, []);
        } catch (Exception $exception) {
            $this->assertEquals(500, $exception->getStatusCode());
            return;
        }

        $this->fail('Exception was not caught.');
    }

    public function test_it_catches_exception_of_destroing_product_and_rolls_back_transaction()
    {
        $this->dbBeginRollback();

        try {
            $this->ps->destroyProduct($this->p);
        } catch (Exception $exception) {
            $this->assertEquals(500, $exception->getStatusCode());
            return;
        }

        $this->fail('Exception was not caught.');
    }

    public function test_destroy_product_by_title()
    {
        $id = $this->p->id;

        $this->ps->destroyProductByTitle($this->p->title);

        $this->assertDatabaseMissing('products', ['id' => $id]);
    }
}
