<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
{
    $query = Comment::with('user', 'board')->orderBy('created_at', 'desc');

    if ($request->filled('keyword')) {
    $keyword = $request->input('keyword');
    $query->where(function ($q) use ($keyword) {
        $q->where('comment', 'like', "%{$keyword}%") // ← 修正ここ！
          ->orWhereHas('user', function ($userQuery) use ($keyword) {
              $userQuery->where('name', 'like', "%{$keyword}%");
          });
    });
}

    $comments = $query->paginate(20);

    return view('admin.comments.index', compact('comments'));
}


    public function edit(Comment $comment)
    {
        return view('admin.comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'comment' => 'required|string|max:65535',
        ]);

        $comment->update(['comment' => $request->comment]);

        return redirect()->route('admin.comments.index')->with('success', 'コメントを更新しました');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return redirect()->route('admin.comments.index')->with('success', 'コメントを削除しました');
    }
}
