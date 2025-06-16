<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Board;

class BoardController extends Controller
{
    public function getBoards($type)
    {
        switch ($type) {
            case 'popular':
                $boards = Board::withCount('likes')
                    ->orderByDesc('likes_count')
                    ->take(10)
                    ->get();
                break;
            case 'views':
                $boards = Board::orderByDesc('view_count')
                    ->take(10)
                    ->get();
                break;
            case 'latest':
            default:
                $boards = Board::latest()
                    ->take(10)
                    ->get();
                break;
        }

        return response()->json($boards);
    }
}