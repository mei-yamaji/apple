<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
{
    $user = $request->user();

    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    $user->fill($request->validated());

    // 画像がアップロードされていれば保存
    if ($request->hasFile('profile_image')) {
        $file = $request->file('profile_image');
        $path = $file->store('profile_images', 'public'); // storage/app/public/profile_images に保存
        $user->profile_image = $path; // DBにパスを保存
    }

    $user->save();

    return Redirect::route('profile.edit')->with('status', 'プロフィールを更新しました');
}
    

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function followings(User $user)
    {
        $followings = $user->followings()->paginate(10);
        return view('profile.followings', compact('user', 'followings'));
    }

    public function followers(User $user)
    {
        $followers = $user->followers()->paginate(10);
        return view('profile.followers', compact('user', 'followers'));
    }

    public function show(User $user)
    {
        $boards = $user->boards()->orderBy('created_at', 'desc')->paginate(10);

        return view('user.show', compact('user','boards'));
    }
}
