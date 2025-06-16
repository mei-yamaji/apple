<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Board $board)
    {
        $request->validate([
            'comment' => 'required',
        ]);

        $board->comments()->create([
            'user_id' => Auth::id(), 
            'comment' => $request->comment,
        ]);

        return redirect()->route('boards.show', $board)->with('success', 'コメントが投稿されました');
    }
}
