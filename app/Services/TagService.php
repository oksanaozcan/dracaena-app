<?php

namespace App\Services;

use App\Models\Tag;

class TagService {

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
}
