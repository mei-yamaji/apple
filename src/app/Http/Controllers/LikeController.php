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
    } else {
        // いいね登録
        Like::create([
            'user_id' => $user->id,
            'board_id' => $boardId,
        ]);
    }

    // いいね数更新
    $likeCount = Like::where('board_id', $boardId)->count();
    Board::where('id', $boardId)->update(['like_count' => $likeCount]);

    return response()->json([
        'liked' => !$like,              // 今押した結果どうなったか（true:いいねした、false:取り消し）
        'likeCount' => $likeCount,      // 最新のいいね数
    ]);
}
}