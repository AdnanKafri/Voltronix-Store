<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show admin login form
     */
    public function showLogin()
    {
        // Redirect if already authenticated as admin
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Rate limiting
        $key = 'admin-login:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Attempt login with admin guard
        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            // Check if admin is active
            $admin = Auth::guard('admin')->user();
            
            if (!$admin->isActive()) {
                Auth::guard('admin')->logout();
                throw ValidationException::withMessages([
                    'email' => 'Your admin account has been deactivated. Please contact the system administrator.',
                ]);
            }

            // Update last login timestamp
            $admin->update(['last_login_at' => now()]);

            RateLimiter::clear($key);
            $request->session()->regenerate();
            
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome to Voltronix Admin Dashboard!');
        }

        RateLimiter::hit($key, 300); // 5 minutes lockout

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        // Ensure we're using the admin guard
        Auth::guard('admin')->logout();
        
        // Clear admin session data
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Clear any admin-specific session data
        $request->session()->forget('admin_authenticated');

        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out successfully.');
    }
}
