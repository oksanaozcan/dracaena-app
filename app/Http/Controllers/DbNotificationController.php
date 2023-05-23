<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DbNotificationController extends Controller
{
    public function index()
    {
        return view('notifications');
    }

    public function markasread($id)
    {
        if ($id) {
            auth()->user()->notifications->where('id', $id)->markAsRead();
        }
        return redirect()->back();
    }
}
