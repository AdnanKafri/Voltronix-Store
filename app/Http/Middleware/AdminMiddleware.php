<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        // Check if user has admin role (using email for now - can be extended with roles table)
        $adminEmails = [
            'admin@voltronix.com',
            'support@voltronix.com',
            // Add more admin emails as needed
        ];

        if (!in_array(auth()->user()->email, $adminEmails)) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}
