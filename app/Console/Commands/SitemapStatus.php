<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SitemapService;
use Carbon\Carbon;

/**
 * ✅ Sitemap Status Command
 * Shows comprehensive status of the automated sitemap system
 */
class SitemapStatus extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'sitemap:status';

    /**
     * The console command description.
     */
    protected $description = 'Show comprehensive status of the automated sitemap system';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔍 Voltronix Digital Store - Sitemap Automation Status');
        $this->newLine();

        // Check if sitemap exists
        $sitemapPath = public_path('sitemap.xml');
        $exists = file_exists($sitemapPath);

        $this->line('📄 <info>Sitemap File Status:</info>');
        $this->line('   Location: ' . $sitemapPath);
        $this->line('   Exists: ' . ($exists ? '✅ Yes' : '❌ No'));

        if ($exists) {
            $fileSize = SitemapService::formatBytes(filesize($sitemapPath));
            $lastModified = Carbon::createFromTimestamp(filemtime($sitemapPath));
            
            $this->line('   Size: ' . $fileSize);
            $this->line('   Last Modified: ' . $lastModified->format('Y-m-d H:i:s'));
            $this->line('   Age: ' . $lastModified->diffForHumans());
        }

        $this->newLine();

        // Get sitemap statistics
        try {
            $sitemapService = new SitemapService();
            $stats = $sitemapService->getSitemapStats();

            $this->line('📊 <info>Sitemap Content Statistics:</info>');
            $this->line('   Total URLs: ' . $stats['total_urls']);
            $this->line('   Static Pages: ' . $stats['static_pages']);
            $this->line('   Categories: ' . $stats['categories']);
            $this->line('   Products: ' . $stats['products']);
            $this->line('   Supported Locales: ' . implode(', ', $stats['locales']));
        } catch (\Exception $e) {
            $this->error('❌ Failed to get sitemap statistics: ' . $e->getMessage());
        }

        $this->newLine();

        // Check scheduler configuration
        $this->line('⏰ <info>Automation Configuration:</info>');
        $this->line('   Environment: ' . app()->environment());
        $this->line('   Production Only: ✅ Yes (scheduler only runs in production)');
        $this->line('   Schedule: Daily at 3:00 AM');
        $this->line('   Command: sitemap:generate --force');

        $this->newLine();

        // Check notification configuration
        $adminEmail = config('services.admin.email');
        $notificationsEnabled = config('services.admin.sitemap_notifications', false);
        
        $this->line('📧 <info>Email Notifications:</info>');
        $this->line('   Admin Email: ' . ($adminEmail ?: 'Not configured'));
        $this->line('   Notifications: ' . ($notificationsEnabled ? '✅ Enabled' : '❌ Disabled'));
        
        if (!$notificationsEnabled) {
            $this->line('   <comment>💡 To enable: Set SITEMAP_EMAIL_NOTIFICATIONS=true in .env</comment>');
        }

        $this->newLine();

        // Show recent logs
        $this->line('📝 <info>Recent Activity:</info>');
        try {
            $logPath = storage_path('logs/laravel.log');
            if (file_exists($logPath)) {
                $logs = file_get_contents($logPath);
                $sitemapLogs = collect(explode("\n", $logs))
                    ->filter(fn($line) => str_contains($line, '[Sitemap'))
                    ->take(-5)
                    ->values();

                if ($sitemapLogs->isNotEmpty()) {
                    foreach ($sitemapLogs as $log) {
                        $this->line('   ' . trim($log));
                    }
                } else {
                    $this->line('   No recent sitemap logs found');
                }
            } else {
                $this->line('   Log file not found');
            }
        } catch (\Exception $e) {
            $this->line('   Unable to read logs: ' . $e->getMessage());
        }

        $this->newLine();

        // Show next steps
        $this->line('🚀 <info>Quick Actions:</info>');
        $this->line('   Generate Now: <comment>php artisan sitemap:generate --force</comment>');
        $this->line('   Test Scheduler: <comment>php artisan scheduler:test</comment>');
        $this->line('   Run Scheduler: <comment>php artisan schedule:run</comment>');
        $this->line('   View Sitemap: <comment>' . url('/sitemap.xml') . '</comment>');

        return Command::SUCCESS;
    }
}
