<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Board;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    /**
     * 掲示板一覧を表示（管理者用）
     */
public function index(Request $request)
{
    // ステータス取得
    $status = $request->input('status');

    // クエリビルダー開始（リレーションも読み込み）
    $query = Board::with(['user', 'tags', 'category'])->orderBy('created_at', 'desc');

    // 公開・非公開の絞り込み
    if ($status === 'published') {
        $query->where('is_published', true);
    } elseif ($status === 'unpublished') {
        $query->where('is_published', false);
    }

    // キーワード検索（タイトル・本文のみ）
    if ($request->filled('keyword')) {
        $keyword = $request->input('keyword');
        $query->where(function ($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%");
        });
    }

    // ページネーション（20件ずつ）
    $boards = $query->paginate(20);

    // ビューへデータ渡し
    return view('admin.boards.index', compact('boards'))->with('status', $status);
}

    /**
     * 編集画面を表示
     */
    public function edit(Board $board)
    {
        return view('admin.boards.edit', compact('board'));
    }

    /**
     * 掲示板を更新
     */
    public function update(Request $request, Board $board)
    {
        $validated = $request->validate([
            'title'   => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:65535'],
        ]);

        $board->update($validated);

        return redirect()->route('admin.boards.index')->with('success', '掲示板を更新しました');
    }

    /**
     * 掲示板を削除
     */
    public function destroy(Board $board)
    {
        $board->delete();

        return redirect()->route('admin.boards.index')->with('success', '掲示板を削除しました');
    }
}
