<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Board;


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
        ->paginate(10);

    return view('mypage', compact('boards'));
}
}
