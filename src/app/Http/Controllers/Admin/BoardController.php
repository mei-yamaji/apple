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
    public function index()
    {
        // ユーザー情報を事前取得して20件ずつページネーション
        $boards = Board::with('user')->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.boards.index', compact('boards'));
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
