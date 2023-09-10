<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Services\TagService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TagController extends Controller
{
    public function index(): View
    {
        return view('tag.index');
    }

    public function create(): View
    {
        return view('tag.create');
    }

    public function store(Request $request)
    {
        /** @see App\Http\Livewire\Tag\CreateForm */
    }

    public function show($id, TagService $tagService): View
    {
        $tag = $tagService->findById($id);
        return view('tag.show', compact('tag'));
    }

    public function edit(Tag $tag): View
    {
        $id = $tag->id;
        return view('tag.edit', compact('id'));
    }

    public function update(Request $request, Tag $tag)
    {
         /** @see App\Http\Livewire\Tag\CreateForm */
    }

    public function destroy($id, TagService $tagService): RedirectResponse
    {
        $tagService->destroyTag($id);
        return redirect()->route('tags.index');
        /**
         * destroy from page tag.index
         * @see App\Http\Livewire\Tag\Table
        * */
    }
}
