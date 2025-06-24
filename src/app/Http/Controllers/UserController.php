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
    $viewMode = $request->query('view', 'own'); // own か likes で切り替え

    $keyword = $request->input('keyword');

    // 自分の投稿
    $boardsQuery = Board::withCount('likes')->where('user_id', $user->id);
    if ($keyword) {
        $boardsQuery->where(function($query) use ($keyword) {
            $query->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
        });
    }
    $boards = $boardsQuery->latest()->paginate(10)->appends(['keyword' => $keyword, 'view' => $viewMode]);

    $boards->map(function ($board) use ($converter) {
        $board->description_html = $converter->convert($board->description ?? '')->getContent();
        return $board;
    });

    // いいねした記事
    $likedBoardsQuery = Board::withCount('likes')->whereIn('id', $user->likes()->pluck('board_id'));
    if ($keyword) {
        $likedBoardsQuery->where(function($query) use ($keyword) {
            $query->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
        });
    }
    $likedBoards = $likedBoardsQuery->latest()->paginate(10, ['*'], 'liked_page')->appends(['keyword' => $keyword, 'view' => $viewMode]);

    $likedBoards->map(function ($board) use ($converter) {
        $board->description_html = $converter->convert($board->description ?? '')->getContent();
        return $board;
    });

    return view('mypage', [
        'boards' => $boards,
        'likedBoards' => $likedBoards,
        'viewMode' => $viewMode,
        'keyword' => $keyword, // ビューに渡す
    ]);
}


    public function show(User $user)
    {
        $query = Board::withCount('likes') 
                  ->where('user_id', $user->id)
                  ->orderByDesc('is_pinned') 
                  ->orderBy('created_at', 'desc');

        // 他人のプロフィールなら、公開済みのみ表示
        if (auth()->id() !== $user->id) {
            $query->where('is_published', true);
        }

        $boards = $query->paginate(10);

        return view('user.show', compact('user', 'boards'));
    }

}
