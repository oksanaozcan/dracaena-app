<?php

namespace App\Services;

use App\Models\Tag;

class TagService {

    public function searchForTable($search, $sortField, $sortDirection)
    {
        $tags = Tag::search('title', $search)
        ->withCount('products')
        ->orderBy($sortField, $sortDirection)
        ->paginate(15);

        return $tags;
    }

    public function storeTag($title, $category_filter_id)
    {
        Tag::create([
            'title' => $title,
            'category_filter_id' => $category_filter_id
        ]);
    }

    public function updateTag($title, $category_filter_id, Tag $tag)
    {
        Tag::find($tag->id)->update([
            'title' => $title,
            'category_filter_id' => $category_filter_id
        ]);
    }

    public function destroyTag(Tag $tag)
    {
        $tag->delete();
    }

    public function destroyTagByTitle($title)
    {
        Tag::where('title', $title)->delete();
    }
}
