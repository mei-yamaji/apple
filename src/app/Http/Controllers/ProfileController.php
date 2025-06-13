<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

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

    return Redirect::route('profile.edit')->with('status', 'profile-updated');
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
}
