<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;

/**
 * ✅ Test Scheduler Command
 * Tests the Laravel scheduler and shows scheduled tasks
 */
class TestScheduler extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'scheduler:test';

    /**
     * The console command description.
     */
    protected $description = 'Test the Laravel scheduler and show scheduled tasks';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔍 Testing Laravel Scheduler for Voltronix Digital Store');
        $this->newLine();

        // Get the scheduler instance
        $schedule = app(Schedule::class);
        $events = $schedule->events();

        if (empty($events)) {
            $this->warn('⚠️  No scheduled tasks found.');
            return Command::SUCCESS;
        }

        $this->info('📅 Scheduled Tasks:');
        $this->newLine();

        foreach ($events as $event) {
            $this->line('• ' . $event->description);
            $this->line('  Command: ' . $event->command);
            $this->line('  Expression: ' . $event->expression);
            $this->line('  Environment: ' . ($event->environments ? implode(', ', $event->environments) : 'all'));
            $this->newLine();
        }

        // Test sitemap generation specifically
        $this->info('🧪 Testing sitemap generation...');
        
        try {
            $this->call('sitemap:generate', ['--force' => true, '--stats' => true]);
            $this->info('✅ Sitemap generation test completed successfully!');
        } catch (\Exception $e) {
            $this->error('❌ Sitemap generation test failed: ' . $e->getMessage());
            return Command::FAILURE;
        }

        $this->newLine();
        $this->info('💡 To run the scheduler manually, use: php artisan schedule:run');
        $this->info('💡 In production, add this to your crontab: * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1');

        return Command::SUCCESS;
    }
}
