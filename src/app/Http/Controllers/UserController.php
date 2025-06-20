<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Board;
use League\CommonMark\CommonMarkConverter;

class UserController extends Controller
{
    public function mypage(Request $request)
    {
        $converter = new CommonMarkConverter();
        $user = auth()->user();
        $viewMode = $request->query('view', 'own'); // 'own'（自分の投稿）か 'likes'（いいね）

        if ($viewMode === 'likes') {
            // ✅ いいねしたBoard一覧
             $likedBoards = Board::withCount('likes')
                    ->whereIn('id', $user->likes()->pluck('board_id'))
                    ->latest()
                    ->get();

            $likedBoards->map(function ($board) use ($converter) {
                $board->description_html = $converter->convert($board->description ?? '')->getContent();
                return $board;
            });

            return view('mypage', [
                'boards' => collect(), // 空のコレクション
                'likedBoards' => $likedBoards,
                'viewMode' => 'likes',
            ]);
        } else {
            // ✅ 自分の投稿（ページネーションあり）
            $boards = Board::withCount('likes')
                ->where('user_id', $user->id)
                ->latest()
                ->paginate(10);

            $boards->map(function ($board) use ($converter) {
                $board->description_html = $converter->convert($board->description ?? '')->getContent();
                return $board;
            });

            return view('mypage', [
                'boards' => $boards,
                'likedBoards' => collect(), // 空のコレクション
                'viewMode' => 'own',
            ]);
        }
    }

    public function show(User $user)
    {
        $boards = Board::where('user_id', $user->id)
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);
                   
        return view('user.show', compact('user', 'boards'));
    }
}
