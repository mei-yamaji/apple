<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CommentNotification;

class CommentController extends Controller
{
    public function store(Request $request, Board $board)
    {
        $request->validate([
            'comment' => 'required',
        ]);

        $comment = $board->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        // 投稿者に通知（自分自身以外の場合）
        if ($board->user->id !== Auth::id()) {
            $board->user->notify(new CommentNotification(auth()->user(), $board));
        }

        // メンションを検出 (@username)
        preg_match_all('/@([\w\-]+)/u', $request->comment, $matches);

        if (!empty($matches[1])) {
            $mentionedUsernames = array_unique($matches[1]);

            $mentionedUsers = \App\Models\User::whereIn('name', $mentionedUsernames)
                ->where('id', '!=', Auth::id()) // 自分へのメンションは無視
                ->get();

            foreach ($mentionedUsers as $mentionedUser) {
                // 通知を送信（例：MentionNotification）
                $mentionedUser->notify(new \App\Notifications\MentionNotification(Auth::user(), $board, $comment));
            }
        }

        return back()->with('success', 'コメントを投稿しました。');
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
