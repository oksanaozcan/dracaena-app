<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Services\TagService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

 /** @see realization of store and update method to App\Http\Livewire\Tag\CreateForm */
    /**destroy from page tag.index @see App\Http\Livewire\Tag\Table * */

class TagController extends Controller
{
    public function __construct(public TagService $tagService)
    {
        $this->middleware('auth')->except(['index','show']);
    }

    public function index(): View
    {
        return view('tag.index');
    }

    public function create(): View
    {
        $this->authorize('create', Tag::class);
        return view('tag.create');
    }

    public function show(Tag $tag): View
    {
        return view('tag.show', compact('tag'));
    }

    public function edit(Tag $tag): View
    {
        $this->authorize('update', $tag);
        $id = $tag->id;
        return view('tag.edit', compact('id'));
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $this->authorize('delete', $tag);
        $this->tagService->destroyTag($tag);
        return redirect()->route('tags.index');
    }
}
