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

    protected function setUp(): void
    {
        parent::setUp();
        $this->c = $this->createCategory();
        $this->cs = new CategoryService();
    }

    public function test_it_catches_exception_of_storing_category_and_rolls_back_transaction()
    {
        $title = 'Test Category';

        Storage::fake('public');
        $preview = UploadedFile::fake()->image('preview.jpg');

        $this->dbBeginRollback();
        Storage::shouldReceive('disk->put')->andReturnUsing(function () {
            throw new Exception('Failed to store preview image');
        });

        try {
            $this->cs->storeCategory($title, $preview);
        } catch (Exception $exception) {
            $this->assertEquals(500, $exception->getStatusCode());
            return;
        }

        $this->fail('Exception was not caught.');
    }

    public function test_it_catches_exception_of_updating_category_and_rolls_back_transaction()
    {
        $title = 'Test Category';

        Storage::fake('public');
        $preview = UploadedFile::fake()->image('preview.jpg');

        $this->dbBeginRollback();
        Storage::shouldReceive('disk->put')->andReturnUsing(function () {
            throw new Exception('Failed to store preview image');
        });

        try {
            $this->cs->updateCategory($title, $this->c, $preview);
        } catch (Exception $exception) {
            $this->assertEquals(500, $exception->getStatusCode());
            return;
        }

        $this->fail('Exception was not caught.');
    }

    public function test_it_catches_exception_of_destroing_category_and_rolls_back_transaction()
    {
        $this->dbBeginRollback();

        try {
            $this->cs->destroyCategory($this->c);
        } catch (Exception $exception) {
            $this->assertEquals(500, $exception->getStatusCode());
            return;
        }

        $this->fail('Exception was not caught.');
    }
}
