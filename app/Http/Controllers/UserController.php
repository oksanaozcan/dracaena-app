<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;

 /** @see realization of store and update method to App\Http\Livewire\User\CreateForm */
    /**destroy from page user.index @see App\Http\Livewire\User\Table * */

class UserController extends Controller
{
    public function __construct(public UserService $userService)
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $this->authorize('viewAny', User::class);
        return view('user.index');
    }

    public function create(): View
    {
        $this->authorize('create', User::class);
        return view('user.create');
    }

    public function show(User $user): View
    {
        $this->authorize('view', $user);
        return view('user.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $this->authorize('update', $user);
        $id = $user->id;
        return view('user.edit', compact('id'));
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);
        $this->userService->destroyUser($user);
        return redirect()->route('users.index');
    }
}
