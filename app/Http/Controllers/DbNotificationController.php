<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DbNotificationController extends Controller
{
    public function index(): View
    {
        return view('notifications');
    }

    public function markasread($id): RedirectResponse
    {
        if ($id) {
            auth()->user()->notifications->where('id', $id)->markAsRead();
        }
        return redirect()->back();
    }
}
