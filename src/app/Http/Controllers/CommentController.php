<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Comment;
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

    public function edit(Board $board, Comment $comment)
    {
    // 自分のコメントだけ編集可
    if (Auth::id() !== $comment->user_id) {
        abort(403);
    }

        return view('comments.edit', compact('board', 'comment'));
    }

    public function update(Request $request, Board $board, Comment $comment)
    {
    if (Auth::id() !== $comment->user_id) {
        abort(403);
    }

    $request->validate([
        'comment' => 'required|string'
    ]);

    $comment->update([
        'comment' => $request->comment,
    ]);

        return redirect()->route('boards.show', $board)->with('success', 'コメントを更新しました。');
    }

    public function destroy(Board $board, Comment $comment)
    {
    if (Auth::id() !== $comment->user_id) {
        abort(403);
    }

    $comment->delete();

        return redirect()->route('boards.show', $board)->with('success', 'コメントを削除しました。');
    }
}
