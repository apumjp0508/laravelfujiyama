<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
                // ログインしていない場合はログインページへリダイレクト
                if (!Auth::check()) {
                    return redirect('/login')->withErrors(['message' => 'ログインが必要です。']);
                }
        
                // ログインしているが管理者でない場合、ホームページへリダイレクト
                if (Auth::user()->role !== 'admin') {
                    
                    return redirect('/home')->withErrors(['message' => '管理者権限が必要です。']);
                }
        
                return $next($request);
    }
}
