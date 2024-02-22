<?php

namespace Tests\Feature\Services;

use App\Services\CategoryFilterService;
use App\Models\CategoryFilter;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Tests\TestHelper;

class CategoryFilterServiceTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cf = $this->createCategoryFilter();
        $this->cfs = new CategoryFilterService();
    }

    public function test_it_catches_exception_of_storing_category_filter_and_rolls_back_transaction()
    {
        $title = 'Test Category filter';
        $cat_id = 1;

        $this->dbBeginRollback();

        try {
            $this->cfs->storeCategoryFilter($title, $cat_id);
        } catch (Exception $exception) {
            $this->assertEquals(500, $exception->getStatusCode());
            return;
        }

        $this->fail('Exception was not caught.');
    }

    public function test_it_catches_exception_of_updating_category_filter_and_rolls_back_transaction()
    {
        $title = 'Test Category Filter';
        $cat_id = 1;

        $this->dbBeginRollback();

        try {
            $this->cfs->updateCategoryFilter($title, $this->cf, $cat_id);
        } catch (Exception $exception) {
            $this->assertEquals(500, $exception->getStatusCode());
            return;
        }

        $this->fail('Exception was not caught.');
    }

    public function test_it_catches_exception_of_destroing_category_filter_and_rolls_back_transaction()
    {
        $this->dbBeginRollback();

        try {
            $this->cfs->destroyCategoryFilter($this->cf);
        } catch (Exception $exception) {
            $this->assertEquals(500, $exception->getStatusCode());
            return;
        }

        $this->fail('Exception was not caught.');
    }
}
