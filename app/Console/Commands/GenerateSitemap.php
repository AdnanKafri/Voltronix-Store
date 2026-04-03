<?php

namespace App\Console\Commands;

use App\Services\SitemapService;
use Illuminate\Console\Command;

/**
 * ✅ Generate Sitemap Artisan Command
 * Creates XML sitemap with bilingual support for Voltronix Digital Store
 */
class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'sitemap:generate 
                            {--force : Force regeneration even if sitemap exists}
                            {--stats : Show sitemap statistics after generation}';

    /**
     * The console command description.
     */
    protected $description = 'Generate XML sitemap for Voltronix Digital Store with bilingual support';

    /**
     * Execute the console command.
     */
    public function handle(SitemapService $sitemapService): int
    {
        $this->info('🚀 Generating sitemap for Voltronix Digital Store...');
        
        // Check if sitemap exists and force flag not set
        if (file_exists(public_path('sitemap.xml')) && !$this->option('force')) {
            if (!$this->confirm('Sitemap already exists. Do you want to regenerate it?')) {
                $this->info('Sitemap generation cancelled.');
                return Command::SUCCESS;
            }
        }

        // Show progress bar
        $this->output->progressStart(4);
        
        $this->output->progressAdvance();
        $this->line(' Adding static pages...');
        
        $this->output->progressAdvance();
        $this->line(' Adding category pages...');
        
        $this->output->progressAdvance();
        $this->line(' Adding product pages...');
        
        $this->output->progressAdvance();
        $this->line(' Generating XML and saving...');
        
        $this->output->progressFinish();

        // Generate sitemap
        $success = $sitemapService->generateSitemap();

        if ($success) {
            $this->info('✅ Sitemap generated successfully!');
            $this->line('📍 Location: ' . public_path('sitemap.xml'));
            
            // Show statistics if requested
            if ($this->option('stats')) {
                $this->showSitemapStats($sitemapService);
            }
            
            return Command::SUCCESS;
        } else {
            $this->error('❌ Failed to generate sitemap. Check logs for details.');
            return Command::FAILURE;
        }
    }

    /**
     * Display sitemap statistics
     */
    private function showSitemapStats(SitemapService $sitemapService): void
    {
        $stats = $sitemapService->getSitemapStats();
        
        $this->newLine();
        $this->info('📊 Sitemap Statistics:');
        $this->table(
            ['Type', 'Count'],
            [
                ['Static Pages', $stats['static_pages']],
                ['Categories', $stats['categories']],
                ['Products', $stats['products']],
                ['Total URLs', $stats['total_urls']],
                ['Locales', implode(', ', $stats['locales'])],
                ['File Size', $this->getFileSize()],
            ]
        );
    }

    /**
     * Get sitemap file size
     */
    private function getFileSize(): string
    {
        $path = public_path('sitemap.xml');
        if (file_exists($path)) {
            $bytes = filesize($path);
            return $this->formatBytes($bytes);
        }
        return 'N/A';
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
