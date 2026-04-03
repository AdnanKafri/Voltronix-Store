<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthRequired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            // For AJAX requests, return JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('app.auth.login_required'),
                    'redirect' => route('login')
                ], 401);
            }
            
            // For regular requests, redirect to login with session flash
            session()->flash('auth_required', true);
            return redirect()->route('login')->with('message', __('app.auth.login_message'));
        }

        return $next($request);
    }
}
