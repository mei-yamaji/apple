<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Board;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $viewMode = $request->query('view', 'own'); // 'own' or 'likes'
        $keyword = $request->query('keyword');

        // 自分の投稿（キーワード検索あり）
        $boards = Board::where('user_id', $user->id)
            ->when($viewMode === 'own' && $keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('title', 'like', "%{$keyword}%")
                      ->orWhere('body', 'like', "%{$keyword}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // いいねした記事IDを取得
        $likedBoardIds = $user->likes()->pluck('board_id');

        // いいねした記事（キーワード検索あり）
        $likedBoards = Board::whereIn('id', $likedBoardIds)
            ->when($viewMode === 'likes' && $keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('title', 'like', "%{$keyword}%")
                      ->orWhere('body', 'like', "%{$keyword}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('mypage', compact('boards', 'likedBoards', 'viewMode', 'keyword'));
    }
}
