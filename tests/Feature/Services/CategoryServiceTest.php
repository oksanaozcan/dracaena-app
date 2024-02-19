<?php

namespace Tests\Feature\Services;

use App\Services\CategoryService;
use App\Models\Category;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Tests\TestHelper;

class CategoryServiceTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    public function test_it_catches_exception_of_storing_category_and_rolls_back_transaction()
    {
        $title = 'Test Category';

        Storage::fake('public');
        $preview = UploadedFile::fake()->image('preview.jpg');

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollBack')->once();
        Storage::shouldReceive('disk->put')->andReturnUsing(function () {
            throw new Exception('Failed to store preview image');
        });

        $categoryService = new CategoryService();

        try {
            $categoryService->storeCategory($title, $preview);
        } catch (Exception $exception) {
            $this->assertEquals(500, $exception->getStatusCode());
            return;
        }

        $this->fail('Exception was not caught.');
    }

    public function test_it_catches_exception_of_updating_category_and_rolls_back_transaction()
    {
        $category = $this->createCategory();
        $title = 'Test Category';

        Storage::fake('public');
        $preview = UploadedFile::fake()->image('preview.jpg');

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollBack')->once();
        Storage::shouldReceive('disk->put')->andReturnUsing(function () {
            throw new Exception('Failed to store preview image');
        });

        $categoryService = new CategoryService();

        try {
            $categoryService->updateCategory($title, $category, $preview);
        } catch (Exception $exception) {
            $this->assertEquals(500, $exception->getStatusCode());
            return;
        }

        $this->fail('Exception was not caught.');
    }

    public function test_it_catches_exception_of_destroing_category_and_rolls_back_transaction()
    {
        $category = $this->createCategory();

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollBack')->once();

        $categoryService = new CategoryService();

        try {
            $categoryService->destroyCategory($category);
        } catch (Exception $exception) {
            $this->assertEquals(500, $exception->getStatusCode());
            return;
        }

        $this->fail('Exception was not caught.');
    }
}
