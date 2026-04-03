<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// ✅ Automated Sitemap Generation Scheduler
// Regenerates sitemap daily at 3:00 AM in production environment
app(Schedule::class)->command('sitemap:generate --force')
    ->dailyAt('03:00')
    ->when(function () {
        return app()->environment('production');
    })
    ->onSuccess(function () {
        \Log::info('[Sitemap Scheduler] Sitemap regenerated successfully at ' . now()->toDateTimeString());
        
        // Optional: Send email notification to admin (uncomment to enable)
        // $adminEmail = config('mail.admin_email', 'admin@voltronix.com');
        // if ($adminEmail && class_exists(\App\Notifications\SitemapGenerated::class)) {
        //     try {
        //         $sitemapService = new \App\Services\SitemapService();
        //         $stats = $sitemapService->getSitemapStats();
        //         $stats['file_size'] = file_exists(public_path('sitemap.xml')) 
        //             ? \App\Services\SitemapService::formatBytes(filesize(public_path('sitemap.xml')))
        //             : 'Unknown';
        //         $stats['execution_time_ms'] = 'N/A';
        //         
        //         \Notification::route('mail', $adminEmail)
        //             ->notify(new \App\Notifications\SitemapGenerated($stats, true));
        //     } catch (\Exception $e) {
        //         \Log::warning('[Sitemap Scheduler] Failed to send success notification: ' . $e->getMessage());
        //     }
        // }
    })
    ->onFailure(function () {
        \Log::error('[Sitemap Scheduler] Sitemap regeneration failed at ' . now()->toDateTimeString());
        
        // Optional: Send failure notification to admin (uncomment to enable)
        // $adminEmail = config('mail.admin_email', 'admin@voltronix.com');
        // if ($adminEmail && class_exists(\App\Notifications\SitemapGenerated::class)) {
        //     try {
        //         \Notification::route('mail', $adminEmail)
        //             ->notify(new \App\Notifications\SitemapGenerated([], false, 'Sitemap generation failed during scheduled execution'));
        //     } catch (\Exception $e) {
        //         \Log::warning('[Sitemap Scheduler] Failed to send failure notification: ' . $e->getMessage());
        //     }
        // }
    })
    ->name('sitemap-auto-generation')
    ->description('Automatically regenerate sitemap for SEO optimization');

// ✅ Automated Currency Rates Update Scheduler
// Updates all currency exchange rates daily at midnight from exchangerate.host API
app(Schedule::class)->command('currency:update-rates')
    ->dailyAt('00:00')
    ->onSuccess(function () {
        \Log::info('[Currency Scheduler] Currency rates updated successfully at ' . now()->toDateTimeString());
    })
    ->onFailure(function () {
        \Log::error('[Currency Scheduler] Currency rates update failed at ' . now()->toDateTimeString());
        
        // Optional: Send failure notification to admin
        try {
            $admin = \App\Models\Admin::where('is_active', true)
                ->where('role', 'super_admin')
                ->first();
                
            if ($admin && class_exists(\Filament\Notifications\Notification::class)) {
                \Filament\Notifications\Notification::make()
                    ->title('❌ Currency Update Failed')
                    ->body('Automatic currency rates update failed. Please check logs or update manually.')
                    ->danger()
                    ->sendToDatabase($admin);
            }
        } catch (\Exception $e) {
            \Log::warning('[Currency Scheduler] Failed to send failure notification: ' . $e->getMessage());
        }
    })
    ->name('currency-auto-update')
    ->description('Automatically update currency exchange rates from exchangerate.host API');
