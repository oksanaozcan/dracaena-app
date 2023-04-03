<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        return view('tag.index');
    }

    public function create()
    {
        return view('tag.create');
    }

    public function store(Request $request)
    {
        /** @see App\Http\Livewire\Tag\CreateForm */
    }

    public function show(Tag $tag)
    {
        $id = $tag->id;
        return view('tag.show', compact('id'));
    }

    public function edit(Tag $tag)
    {
        $id = $tag->id;
        return view('tag.edit', compact('id'));
    }

    public function update(Request $request, Tag $tag)
    {
         /** @see App\Http\Livewire\Tag\CreateForm */
    }

    public function destroy(Tag $tag)
    {
        //
    }
}
