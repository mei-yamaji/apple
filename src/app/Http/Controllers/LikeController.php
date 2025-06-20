<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Board;
use App\Notifications\LikeNotification;

class LikeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'board_id' => ['required', 'integer', 'exists:boards,id'],
        ]);

        $user = auth()->user();
        $boardId = $request->input('board_id');
        $board = Board::findOrFail($boardId); // ← 追加（必須）

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

            // 自分の投稿でなければ通知送信
            if ($board->user->id !== $user->id) {
                $board->user->notify(new LikeNotification($user, $board));
            }
        }

        // いいね数更新
        $likeCount = Like::where('board_id', $boardId)->count();
        $board->like_count = $likeCount;
        $board->save();

        return response()->json([
            'liked' => !$like,
            'likeCount' => $likeCount,
        ]);
    }
}
