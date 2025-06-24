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

        $notifications = auth()->user()->notifications()->latest()->paginate(20);
        return view('notifications.index', compact('notifications'));

    }

    public function markAsRead(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();
 
        return response()->json(['message' => '通知を既読にしました']);
    }

    // 選択した通知のみ既読にする
    public function markSelectedAsRead(Request $request)
    {
        $ids = $request->input('notification_ids', []);

        if (empty($ids)) {
            return response()->json(['status' => 'error', 'message' => '通知が選択されていません。']);
        }

        foreach ($ids as $id) {
            $notification = auth()->user()->notifications()->find($id);
            if ($notification) {
                $notification->markAsRead();
            }
        }

        return response()->json(['status' => 'success']);
    }



}

 