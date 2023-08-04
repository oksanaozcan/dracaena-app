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

    public function findById($id)
    {
        return Tag::with(['products' => function ($query) {
            $query->select('product_id','title');
        }])->findOrFail($id);
    }

    public function storeTag($title)
    {
        Tag::create([
            'title' => $title,
        ]);

    }

    public function updateTag($title, Tag $tag)
    {
        Tag::find($tag->id)->update([
            'title' => $title,
        ]);
    }

    public function destroyTag($id)
    {
        Tag::find($id)->delete();
    }

    public function destroyTagByTitle($title)
    {
        Tag::where('title', $title)->delete();
    }
}
