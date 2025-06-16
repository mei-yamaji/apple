<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Board;

class LikeController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'board_id' => ['required', 'integer', 'exists:boards,id'],
    ]);

    $user = auth()->user();
    $boardId = $request->input('board_id');

    $like = Like::where('user_id', $user->id)
                ->where('board_id', $boardId)
                ->first();

    if ($like) {
        // いいね済みなら削除（取り消し）
        $like->delete();

        // いいね数更新
        $likeCount = Like::where('board_id', $boardId)->count();
        Board::where('id', $boardId)->update(['like_count' => $likeCount]);

        return back()->with('message', 'いいねを取り消しました');
    } else {
        // いいね登録
        Like::create([
            'user_id' => $user->id,
            'board_id' => $boardId,
        ]);

        // いいね数更新
        $likeCount = Like::where('board_id', $boardId)->count();
        Board::where('id', $boardId)->update(['like_count' => $likeCount]);

        return back()->with('message', 'いいねしました');
    }
}
}