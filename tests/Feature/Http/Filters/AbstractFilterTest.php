<?php

namespace Tests\Feature\Http\Filters;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Filters\ProductFilter;
use Tests\TestHelper;
use Mockery;
use Illuminate\Database\Eloquent\Builder;
use Database\Seeders\RoleSeeder;

class AbstractFilterTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    public function test_1_apply_filters()
    {
        $data = $this->createCategoryAndProduct();
        $cat = $data['category'];
        $p = $data['product'];

        $queryParams = ['category_id' => $cat->id];
        $filter = new ProductFilter($queryParams);

        $builder = Mockery::mock(Builder::class);
        $builder->shouldReceive('where')->withArgs(['category_id', $cat->id])->once();

        $filter->apply($builder);
    }

    public function test_2_before_callback()
    {
        $filter = new ProductFilter([]);

        $builder = Mockery::mock(Builder::class);
        $builder->shouldReceive('where')->never();

        $filter->apply($builder);
    }

    public function test_3_GetCallbacks()
    {
        $filter = new ProductFilter([]);

        $callbacks = $filter->exposeGetCallbacks();

        $this->assertIsArray($callbacks);
    }

    public function test_4_GetQueryParam()
    {
        $queryParams = ['category_id' => '1'];
        $filter = new ProductFilter($queryParams);

        $result = $filter->exposeGetQueryParam('category_id');

        $this->assertEquals('1', $result);
    }
}
