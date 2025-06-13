<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    public function getArticles($type)
    {
        switch ($type) {
            case 'popular':
                $articles = Article::withCount('likes')
                    ->orderByDesc('likes_count')
                    ->take(10)
                    ->get();
                break;
            case 'views':
                $articles = Article::orderByDesc('view_count')
                    ->take(10)
                    ->get();
                break;
            case 'latest':
            default:
                $articles = Article::latest()
                    ->take(10)
                    ->get();
                break;
        }

        return response()->json($articles);
    }
}