<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 
class NotificationController extends Controller

{

    public function index()

    {

        $notifications = auth()->user()->notifications()->latest()->get();

        return response()->json($notifications);

    }
 
    public function read($id)

    {

        $notification = auth()->user()->notifications()->findOrFail($id);

        $notification->markAsRead();
 
        return response()->json(['status' => 'success']);

    }

    public function notifications()
    {

        $notifications = auth()->user()->unreadNotifications;
        return view('notifications.index', compact('notifications'));

    }

    public function markAsRead(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();
 
        return response()->json(['message' => '通知を既読にしました']);
    }

}

 