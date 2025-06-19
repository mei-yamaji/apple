<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('admin.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
   public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $request->session()->regenerate();

    // ここは、LoginRequest内で使っているガードに合わせたリダイレクト先を指定すればOK
    // 管理者ログインなら管理者ホームへ、そうでなければ一般ユーザーのホームへリダイレクト
    $guard = $request->is('admin/*') ? 'admin' : 'web';

    if ($guard === 'admin') {
        return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
    } else {
        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}