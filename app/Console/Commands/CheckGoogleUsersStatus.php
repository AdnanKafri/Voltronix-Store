<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckGoogleUsersStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:check-google-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check verification status of all Google OAuth users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Checking Google OAuth users verification status...');
        $this->newLine();
        
        $googleUsers = User::whereNotNull('google_id')
            ->orderBy('created_at', 'desc')
            ->get(['name', 'email', 'google_id', 'email_verified_at', 'created_at']);
            
        if ($googleUsers->isEmpty()) {
            $this->warn('⚠️  No Google OAuth users found in the database.');
            return 0;
        }
        
        $verifiedCount = 0;
        $unverifiedCount = 0;
        
        $this->info("📊 Found {$googleUsers->count()} Google OAuth user(s):");
        $this->newLine();
        
        foreach ($googleUsers as $user) {
            $status = $user->email_verified_at ? '✅ VERIFIED' : '❌ UNVERIFIED';
            $joinDate = $user->created_at->format('M d, Y');
            
            if ($user->email_verified_at) {
                $verifiedCount++;
                $this->line("   {$status} - {$user->name} ({$user->email}) - Joined: {$joinDate}");
            } else {
                $unverifiedCount++;
                $this->error("   {$status} - {$user->name} ({$user->email}) - Joined: {$joinDate}");
            }
        }
        
        $this->newLine();
        $this->info("📈 Summary:");
        $this->line("   ✅ Verified Google users: {$verifiedCount}");
        $this->line("   ❌ Unverified Google users: {$unverifiedCount}");
        
        if ($unverifiedCount > 0) {
            $this->newLine();
            $this->warn("⚠️  Run 'php artisan users:fix-google-verification --force' to fix unverified Google users.");
        } else {
            $this->newLine();
            $this->info("🎉 All Google OAuth users are properly verified!");
        }
        
        return 0;
    }
}
