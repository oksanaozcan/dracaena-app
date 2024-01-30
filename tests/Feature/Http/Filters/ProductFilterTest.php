<?php

namespace Tests\Feature\Http\Filters;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestHelper;
use App\Http\Filters\ProductFilter;
use App\Models\Product;

class ProductFilterTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    public function test_1_it_can_filter_by_search()
    {
        $data = $this->createCategoryAndProduct();
        $p = $data["product"];

        $filter = new ProductFilter([]);
        $builder = Product::query();

        $filter->search($builder, $p->title, ['title', 'description']);

        $this->assertStringContainsString("select * from `products` where (`title` like ? or `description` like ?)", $builder->toSql());
    }

    public function test_2_it_can_filter_by_category_id()
    {
        $data = $this->createCategoryAndProduct();
        $cat = $data["category"];
        $p = $data["product"];

        $filter = new ProductFilter([]);
        $builder = Product::query();

        $filter->categoryId($builder, $cat->id);

        $this->assertStringContainsString("select * from `products` where `category_id` = ?", $builder->toSql());
    }

    public function test_3_it_can_filter_by_tag_id()
    {
        $t = $this->createTag();

        $filter = new ProductFilter([]);
        $builder = Product::query();

        $filter->tagId($builder, $t->id);

        $this->assertStringContainsString('product_tags', $builder->toSql());
        $this->assertStringContainsString('tag_id', $builder->toSql());
        $this->assertStringContainsString("select `products`.* from `products` inner join `product_tags` on `products`.`id` = `product_tags`.`product_id` where `product_tags`.`tag_id` = ?", $builder->toSql());
    }

    public function test_4_it_can_sort_by_price_asc()
    {
        $filter = new ProductFilter([]);
        $builder = Product::query();

        $filter->sort($builder, 'price-asc');

        $this->assertStringContainsString("select * from `products` order by `price` asc", $builder->toSql());
    }
}
