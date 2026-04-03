<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Exception;

/**
 * ✅ Google OAuth Controller for Voltronix Digital Store
 * Handles Google login integration with graceful error handling
 */
class GoogleController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        // Check if Google OAuth is configured
        if (!$this->isGoogleConfigured()) {
            return $this->handleMissingConfiguration();
        }

        try {
            return Socialite::driver('google')->redirect();
        } catch (Exception $e) {
            \Log::error('[Google OAuth] Redirect failed: ' . $e->getMessage());
            
            return redirect()->route('login')->with('error', 
                __('app.auth.google_login_error')
            );
        }
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        // Check if Google OAuth is configured
        if (!$this->isGoogleConfigured()) {
            return $this->handleMissingConfiguration();
        }

        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Find or create user
            $user = $this->findOrCreateUser($googleUser);
            
            // Log the user in
            Auth::login($user, true);
            
            // Log successful login
            \Log::info('[Google OAuth] User logged in successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'google_id' => $googleUser->getId(),
                'is_verified' => !is_null($user->email_verified_at),
                'verification_date' => $user->email_verified_at
            ]);
            
            // Redirect with success message
            return redirect()->intended(route('home'))->with('success', 
                __('app.auth.google_login_success', ['name' => $user->name])
            );
            
        } catch (Exception $e) {
            \Log::error('[Google OAuth] Callback failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('login')->with('error', 
                __('app.auth.google_login_error')
            );
        }
    }

    /**
     * Find or create user from Google data
     */
    private function findOrCreateUser($googleUser): User
    {
        // Check if user exists with Google ID
        $existingUser = User::where('google_id', $googleUser->getId())->first();
        
        if ($existingUser) {
            // Update user info if needed
            $this->updateUserFromGoogle($existingUser, $googleUser);
            return $existingUser;
        }
        
        // Check if user exists with same email
        $existingUser = User::where('email', $googleUser->getEmail())->first();
        
        if ($existingUser) {
            // Link Google account to existing user and mark as verified
            $wasVerified = !is_null($existingUser->email_verified_at);
            
            $existingUser->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'email_verified_at' => $existingUser->email_verified_at ?: now(), // Verify if not already verified
            ]);
            
            \Log::info('[Google OAuth] Existing user linked to Google account', [
                'user_id' => $existingUser->id,
                'email' => $existingUser->email,
                'google_id' => $googleUser->getId(),
                'was_verified_before' => $wasVerified,
                'is_verified_now' => !is_null($existingUser->email_verified_at),
                'verified_at' => $existingUser->email_verified_at
            ]);
            
            return $existingUser;
        }
        
        // Create new user
        $newUser = User::create([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
            'password' => Hash::make(Str::random(32)), // Random password for Google users
            'email_verified_at' => now(), // Google accounts are pre-verified
        ]);
        
        \Log::info('[Google OAuth] New user created and verified', [
            'user_id' => $newUser->id,
            'email' => $newUser->email,
            'google_id' => $googleUser->getId(),
            'verified_at' => $newUser->email_verified_at
        ]);
        
        return $newUser;
    }

    /**
     * Update existing user with Google data
     */
    private function updateUserFromGoogle(User $user, $googleUser): void
    {
        $user->update([
            'avatar' => $googleUser->getAvatar(),
            // Update name only if it's different and not empty
            'name' => $googleUser->getName() ?: $user->name,
            // Ensure Google users are always verified
            'email_verified_at' => $user->email_verified_at ?: now(),
        ]);
    }

    /**
     * Check if Google OAuth is properly configured
     */
    private function isGoogleConfigured(): bool
    {
        return !empty(config('services.google.client_id')) && 
               !empty(config('services.google.client_secret'));
    }

    /**
     * Handle missing Google OAuth configuration
     */
    private function handleMissingConfiguration()
    {
        \Log::warning('[Google OAuth] Missing configuration - Google login attempted without proper setup');
        
        return redirect()->route('login')->with('warning', 
            __('app.auth.google_not_configured')
        );
    }

    /**
     * Disconnect Google account (for future use)
     */
    public function disconnect(Request $request)
    {
        $user = $request->user();
        
        if (!$user->google_id) {
            return redirect()->back()->with('error', 
                __('app.auth.google_not_connected')
            );
        }
        
        // Don't disconnect if it's the only login method and no password
        if (!$user->password && $user->google_id) {
            return redirect()->back()->with('error', 
                __('app.auth.google_only_login_method')
            );
        }
        
        $user->update([
            'google_id' => null,
            'avatar' => null,
        ]);
        
        return redirect()->back()->with('success', 
            __('app.auth.google_disconnected')
        );
    }
}
