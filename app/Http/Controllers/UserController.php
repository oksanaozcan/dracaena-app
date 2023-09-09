<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\UserService;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
         /** @see App\Http\Livewire\User\CreateForm */
    }

    public function show(string $id, UserService $userService)
    {
        $user = $userService->findById($id);
        return view('user.show', compact('user'));
    }

    public function edit(User $user)
    {
        $id = $user->id;
        return view('user.edit', compact('id'));
    }

    public function update(Request $request, User $user)
    {
         /** @see App\Http\Livewire\User\CreateForm */
    }

    public function destroy($id, UserService $userService)
    {
        $userService->destroyUser($id);
        return redirect()->route('users.index');
          /**
         * destroy from page user.index
         * @see App\Http\Livewire\User\Table
        * */
    }
}
