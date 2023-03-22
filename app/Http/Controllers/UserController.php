<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('user.index', [
            'users' => User::all(),
        ]);
    }

    public function create(): View
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(User $user)
    {
        //
    }

    public function edit(User $user)
    {
        //
    }

    public function update(Request $request, User $user)
    {
        //
    }

    public function delete(User $user)
    {
        //
    }

    public function destroy(User $user)
    {
        //
    }
}
