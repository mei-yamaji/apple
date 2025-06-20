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
    $viewMode = $request->query('view', 'own'); // 初期表示判定用

    // ✅ 自分の投稿（ページネーションあり）
    $boards = Board::withCount('likes')
        ->where('user_id', $user->id)
        ->latest()
        ->paginate(10);

    $boards->map(function ($board) use ($converter) {
        $board->description_html = $converter->convert($board->description ?? '')->getContent();
        return $board;
    });

    // ✅ いいねした記事（全部取得）
    $likedBoards = Board::withCount('likes')
        ->whereIn('id', $user->likes()->pluck('board_id'))
        ->latest()
        ->get();

    $likedBoards->map(function ($board) use ($converter) {
        $board->description_html = $converter->convert($board->description ?? '')->getContent();
        return $board;
    });

    return view('mypage', [
        'boards' => $boards,
        'likedBoards' => $likedBoards,
        'viewMode' => $viewMode, // 初期表示を切り替えられるように残しておく
    ]);
    }


    public function show(User $user)
    {
        $boards = Board::where('user_id', $user->id)
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);
                   
        return view('user.show', compact('user', 'boards'));
    }
}
