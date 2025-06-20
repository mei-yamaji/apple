<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
  public function index()
  {
        $boardCount = Board::count();
        $userCount = User::count();
        $categoryCount = Category::count();
        $commentCount = Comment::count();

    return view('admin.dashboard', compact('boardCount', 'userCount', 'categoryCount', 'commentCount'));
  }
}