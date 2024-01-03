<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Billboard;
use App\Services\BillboardService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BillboardController extends Controller
{
    public function __construct(public BillboardService $billboardService)
    {
        $this->middleware('auth')->except(['index','show']);
    }

    public function index(): View
    {
        return view('billboard.index');
    }

    public function create(): View
    {
        $this->authorize('create', Billboard::class);
        return view('billboard.create');
    }

    public function store(Request $request): View
    {
        /** @see App\Http\Livewire\Billboard\CreateForm */
    }

    public function show(Billboard $billboard): View
    {
        return view('billboard.show', compact('billboard'));
    }

    public function edit(Billboard $billboard): View
    {
        $this->authorize('update', $billboard);
        $id = $billboard->id;
        return view('billboard.edit', compact('id'));
    }

    public function update(Request $request, Billboard $billboard)
    {
        /** @see App\Http\Livewire\Billboard\CreateForm */
    }

    public function destroy(Billboard $billboard): RedirectResponse
    {
        $this->authorize('delete', $billboard);
        $this->billboardService->destroyBillboard($billboard);
        return redirect()->route('billboards.index');
        /**
         * destroy from page billboard.index
         * @see App\Http\Livewire\Billboard\Table
        * */
    }
}
