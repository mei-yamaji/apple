<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\FavoriteNotification;

class FavoriteController extends Controller
{
    /**
     * お気に入り登録／解除を切り替えるトグル処理
     *
     * @param  \App\Models\User  $user  お気に入り対象のユーザー
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggle(User $user)

{

    $authUser = Auth::user();
 
    // 自分自身をお気に入り登録させない（任意）

    if ($authUser->id === $user->id) {

        return back()->with('error', '自分自身をフォローできません。');

    }
 
    // すでにお気に入り登録済みなら解除、それ以外は登録

    if ($authUser->hasFavorited($user->id)) {

        $authUser->favorites()->detach($user->id);

        $message = 'フォローを解除しました。';

    } else {

        $authUser->favorites()->attach($user->id);
 
        // 🔔 通知を送る

        $user->notify(new FavoriteNotification(auth()->user(), $user));
 
        $message = 'フォローしました。';

    }
 
    return back()->with('status', $message);

}

 
}
