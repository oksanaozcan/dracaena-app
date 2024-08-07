<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use  App\Http\Filters\ProductFilterTestingTrait;
use Illuminate\Support\Facades\Log;

class ProductFilter extends AbstractFilter
{
    use ProductFilterTestingTrait;

  public const SEARCH = 'search';
  public const CATEGORY_ID = 'category_id';
  public const TAG_ID = 'tag_id';
  public const SORT = 'sort';
  public const CATEGORY_FILTER_ID = 'category_filter_id';

  protected function getCallbacks(): array
  {
    return [
      self::SEARCH => [$this, 'search'],
      self::CATEGORY_ID => [$this, 'categoryId'],
      self::TAG_ID => [$this, 'tagId'],
      self::SORT => [$this, 'sort'],
      self::CATEGORY_FILTER_ID => [$this, 'categoryFilterId']
    ];
  }

  public function search(Builder $builder, $value, $columns = ['title', 'description'])
  {
    $builder->where(function ($query) use ($value, $columns) {
        foreach ($columns as $column) {
            $query->orWhere($column, 'like', "%{$value}%");
        }
    });
  }

  public function categoryId(Builder $builder, $value)
  {
    $builder->where('category_id', $value);
  }

  public function tagId(Builder $builder, $value)
  {
    $builder->join('product_tags', 'products.id', '=', 'product_tags.product_id')
    ->where('product_tags.tag_id', $value)
    ->select('products.*');
  }

  public function categoryFilterId(Builder $builder, $value)
  {
      // Get all tag IDs associated with the given category_filter_id
      $tagIds = DB::table('tags')
                  ->where('category_filter_id', $value)
                  ->pluck('id');

      // Join with product_tags and filter products based on the tag IDs
      $builder->join('product_tags', 'products.id', '=', 'product_tags.product_id')
              ->whereIn('product_tags.tag_id', $tagIds)
              ->select('products.*');
  }

  public function sort(Builder $builder, $value)
  {
    switch ($value) {
        case 'price-asc':
            $builder->orderBy('price', 'asc');
            break;
        case 'price-desc':
            $builder->orderBy('price', 'desc');
            break;
        case 'name-asc':
            $builder->orderBy('title', 'asc');
            break;
        case 'name-desc':
            $builder->orderBy('title', 'desc');
            break;
        case 'date-desc-rank':
            $builder->orderBy('created_at', 'desc');
            break;
        default:
            $builder->orderBy('created_at', 'desc');
            break;
    }
  }

}
