<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * ✅ Google OAuth Configuration Checker
 * Verifies Google OAuth setup and provides configuration guidance
 */
class CheckGoogleOAuth extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'google:check';

    /**
     * The console command description.
     */
    protected $description = 'Check Google OAuth configuration status';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔍 Checking Google OAuth Configuration for Voltronix Digital Store');
        $this->newLine();

        // Check environment variables
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');
        $redirectUri = config('services.google.redirect');

        $this->line('📋 <info>Configuration Status:</info>');
        $this->line('   Client ID: ' . ($clientId ? '✅ Configured' : '❌ Missing'));
        $this->line('   Client Secret: ' . ($clientSecret ? '✅ Configured' : '❌ Missing'));
        $this->line('   Redirect URI: ' . ($redirectUri ? '✅ ' . $redirectUri : '❌ Missing'));

        $this->newLine();

        // Check routes
        $this->line('🛣️  <info>Routes Status:</info>');
        try {
            $redirectRoute = route('auth.google.redirect');
            $callbackRoute = route('auth.google.callback');
            
            $this->line('   Redirect Route: ✅ ' . $redirectRoute);
            $this->line('   Callback Route: ✅ ' . $callbackRoute);
        } catch (\Exception $e) {
            $this->line('   Routes: ❌ Error - ' . $e->getMessage());
        }

        $this->newLine();

        // Check database
        $this->line('🗄️  <info>Database Status:</info>');
        try {
            $hasGoogleId = \Schema::hasColumn('users', 'google_id');
            $hasAvatar = \Schema::hasColumn('users', 'avatar');
            
            $this->line('   Google ID Column: ' . ($hasGoogleId ? '✅ Exists' : '❌ Missing'));
            $this->line('   Avatar Column: ' . ($hasAvatar ? '✅ Exists' : '❌ Missing'));
        } catch (\Exception $e) {
            $this->line('   Database: ❌ Error - ' . $e->getMessage());
        }

        $this->newLine();

        // Overall status
        $isConfigured = $clientId && $clientSecret;
        
        if ($isConfigured) {
            $this->line('🎉 <info>Google OAuth is properly configured and ready to use!</info>');
            $this->newLine();
            $this->line('🔗 <comment>Test URLs:</comment>');
            $this->line('   Login Page: ' . url('/login'));
            $this->line('   Google OAuth: ' . url('/auth/google'));
        } else {
            $this->line('⚠️  <comment>Google OAuth is not fully configured.</comment>');
            $this->newLine();
            $this->line('📝 <comment>To complete setup:</comment>');
            $this->line('   1. Go to Google Cloud Console (https://console.cloud.google.com/)');
            $this->line('   2. Create a new project or select existing one');
            $this->line('   3. Enable Google+ API');
            $this->line('   4. Create OAuth 2.0 credentials');
            $this->line('   5. Add these to your .env file:');
            $this->newLine();
            $this->line('   <comment>GOOGLE_CLIENT_ID=your_client_id_here</comment>');
            $this->line('   <comment>GOOGLE_CLIENT_SECRET=your_client_secret_here</comment>');
            $this->line('   <comment>GOOGLE_REDIRECT_URI=' . url('/auth/google/callback') . '</comment>');
            $this->newLine();
            $this->line('   6. Add this redirect URI in Google Console:');
            $this->line('   <comment>' . url('/auth/google/callback') . '</comment>');
        }

        $this->newLine();
        $this->line('💡 <comment>Note: Google OAuth will gracefully handle missing credentials by showing a user-friendly message.</comment>');

        return Command::SUCCESS;
    }
}
