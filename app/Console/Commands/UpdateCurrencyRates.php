<?php

namespace App\Console\Commands;

use App\Services\CurrencyUpdateService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateCurrencyRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:update-rates 
                            {--force : Force update even if recently updated}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all currency exchange rates from exchangerate.host API';

    /**
     * Currency update service
     *
     * @var CurrencyUpdateService
     */
    protected $currencyUpdateService;

    /**
     * Create a new command instance.
     */
    public function __construct(CurrencyUpdateService $currencyUpdateService)
    {
        parent::__construct();
        $this->currencyUpdateService = $currencyUpdateService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔄 Starting currency rates update...');
        $this->newLine();

        try {
            // Force clear cache if --force option is used
            if ($this->option('force')) {
                $this->warn('⚠️  Force update requested - clearing cache...');
                $this->currencyUpdateService->clearUpdateCache();
            }

            // Check if update is needed
            if (!$this->option('force') && !$this->currencyUpdateService->isUpdateNeeded()) {
                $lastUpdate = $this->currencyUpdateService->getLastUpdateTime();
                $this->info('✓ Currency rates are already up to date');
                $this->info("  Last updated: {$lastUpdate->diffForHumans()}");
                $this->newLine();
                
                Log::info('Currency update skipped - already updated recently', [
                    'last_update' => $lastUpdate
                ]);
                
                return Command::SUCCESS;
            }

            // Show progress bar
            $this->info('📡 Fetching latest exchange rates from API...');
            $progressBar = $this->output->createProgressBar(3);
            $progressBar->start();

            // Perform update
            $progressBar->advance();
            $result = $this->currencyUpdateService->updateAllRates();
            $progressBar->advance();
            
            // Clear progress bar
            $progressBar->finish();
            $this->newLine(2);

            // Display results
            if ($result['success']) {
                $this->info('✅ ' . $result['message']);
                $this->newLine();
                
                $this->table(
                    ['Metric', 'Value'],
                    [
                        ['Updated Currencies', $result['updated']],
                        ['Skipped Currencies', count($result['skipped'] ?? [])],
                        ['Errors', count($result['errors'] ?? [])],
                        ['Timestamp', now()->format('Y-m-d H:i:s')]
                    ]
                );

                if (!empty($result['skipped'])) {
                    $this->warn('⚠️  Skipped currencies: ' . implode(', ', $result['skipped']));
                }

                if (!empty($result['errors'])) {
                    $this->error('❌ Errors encountered:');
                    foreach ($result['errors'] as $error) {
                        $this->error('  • ' . $error);
                    }
                }

                // Log success
                Log::info('Currency rates updated via command', [
                    'updated' => $result['updated'],
                    'skipped' => $result['skipped'] ?? [],
                    'errors' => $result['errors'] ?? [],
                    'forced' => $this->option('force')
                ]);

                // Send Filament notification (if admin user exists)
                try {
                    $this->sendAdminNotification($result);
                } catch (\Exception $e) {
                    // Silent fail for notifications
                    Log::warning('Failed to send admin notification', [
                        'error' => $e->getMessage()
                    ]);
                }

                return Command::SUCCESS;
                
            } else {
                $this->error('❌ Failed to update currency rates');
                $this->error('  ' . $result['message']);
                $this->newLine();

                if (!empty($result['errors'])) {
                    $this->error('Errors:');
                    foreach ($result['errors'] as $error) {
                        $this->error('  • ' . $error);
                    }
                }

                Log::error('Currency update command failed', [
                    'message' => $result['message'],
                    'errors' => $result['errors'] ?? []
                ]);

                return Command::FAILURE;
            }

        } catch (\Exception $e) {
            $this->error('❌ An unexpected error occurred:');
            $this->error('  ' . $e->getMessage());
            $this->newLine();

            Log::error('Currency update command exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return Command::FAILURE;
        }
    }

    /**
     * Send notification to admin about update results
     *
     * @param array $result
     * @return void
     */
    private function sendAdminNotification(array $result): void
    {
        // Log the update results for admin review
        $title = $result['updated'] > 0 
            ? "✅ Currency Rates Updated" 
            : "ℹ️ Currency Update Complete";

        $message = $result['message'];

        if (!empty($result['errors'])) {
            $message .= "\n⚠️ " . count($result['errors']) . " error(s) occurred.";
        }

        Log::info($title, [
            'message' => $message,
            'updated' => $result['updated'],
            'skipped' => $result['skipped'] ?? [],
            'errors' => $result['errors'] ?? [],
            'timestamp' => now()
        ]);
    }
}
