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
    $board->load('comments');
    return view('boards.show', compact('board'));
}

}
