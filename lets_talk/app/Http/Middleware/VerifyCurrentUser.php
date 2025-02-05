<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class VerifyCurrentUser extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info('Current user:', ['user' => auth()->user()]);
        Log::info('CSRF token:', ['token' => $request->session()->token()]);

        // if (session()->has('usuario_id')) {
        //     $user = User::find(session('usuario_id'));
        //     if ($user) {
        //         Auth::login($user);
        //     }
        // }

        return $next($request);
    }
}
