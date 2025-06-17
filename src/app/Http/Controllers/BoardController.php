<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index()
    {
        $boards = Board::with('user')->latest()->get();
        return view('boards.index', compact('boards'));
    }

    public function create()
    {
        return view('boards.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title'       => 'required|max:255',
            'description' => 'required|max:65535',
        ]);

        $board = new Board();
        $board->title = $validatedData['title'];
        $board->description = $validatedData['description'];
        $board->user_id = auth()->id();
        $board->save();

        return redirect()->route('boards.index');
    }

    public function show(Board $board)
    {
        $board->increment('view_count');
        $board->load('comments.user');
        return view('boards.show', compact('board'));
    }

    public function fetchRanking($type = 'latest')
    {
        $query = Board::with('user')->withCount('likes'); // ← ここで user 情報も一緒に取得（任意）

        switch ($type) {
            case 'popular':
                $query = $query->popularBoards(); // ← $query にスコープを適用
                break;
            case 'views':
                $query = $query->mostViewedBoards();
                break;
            case 'latest':
            default:
                $query = $query->latestBoards();
                break;
        }

        $boards = $query->take(5)->get();

        return response()->json($boards);
    } 
}
