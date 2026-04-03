<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class FixGoogleUsersVerification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:fix-google-verification {--force : Skip confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix verification status for existing Google OAuth users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Searching for unverified Google users...');
        
        // Find users who have Google ID but are not verified
        $unverifiedGoogleUsers = User::whereNotNull('google_id')
            ->whereNull('email_verified_at')
            ->get();
            
        if ($unverifiedGoogleUsers->isEmpty()) {
            $this->info('✅ No unverified Google users found. All Google users are already verified!');
            return 0;
        }
        
        $count = $unverifiedGoogleUsers->count();
        $this->warn("⚠️  Found {$count} unverified Google user(s):");
        
        // Display the users we're about to fix
        foreach ($unverifiedGoogleUsers as $user) {
            $this->line("   - {$user->name} ({$user->email})");
        }
        
        if (!$this->option('force') && !$this->confirm('Do you want to mark these Google users as verified?')) {
            $this->info('❌ Operation cancelled.');
            return 0;
        }
        
        // Update all unverified Google users
        $updated = User::whereNotNull('google_id')
            ->whereNull('email_verified_at')
            ->update(['email_verified_at' => now()]);
            
        $this->info("✅ Successfully verified {$updated} Google user(s)!");
        $this->info('🎉 All Google OAuth users are now properly marked as verified.');
        
        return 0;
    }
}
