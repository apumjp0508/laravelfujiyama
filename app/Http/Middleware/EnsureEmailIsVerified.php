<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        // 未ログインならログイン画面へ（メッセージ付き）
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'ログインが必要です。');
        }

        // メール未認証なら認証画面へ（メッセージ付き）
        if (!Auth::user() || !Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('error', 'メール認証が必要です。');
        }

        // 認証済みならそのまま
        return $next($request);
    }
}
