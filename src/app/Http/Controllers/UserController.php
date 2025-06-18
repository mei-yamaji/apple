<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Board;
use League\CommonMark\CommonMarkConverter;


class UserController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('user.show', compact('user'));
    }


public function mypage()
    {
        $boards = \App\Models\Board::where('user_id', auth()->id())
            ->latest()
            ->get();

        $converter = new CommonMarkConverter();

        $boards->map(function ($board) use ($converter) {
            $board->description_html = $converter->convert($board->description ?? '')->getContent();
            return $board;
        });

        return view('mypage', compact('boards'));
    }
}
