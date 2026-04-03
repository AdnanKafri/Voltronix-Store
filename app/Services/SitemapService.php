<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

/**
 * ✅ Sitemap Generator Service
 * Generates dynamic XML sitemap for Voltronix Digital Store
 * Supports bilingual content (EN/AR) with proper hreflang alternates
 */
class SitemapService
{
    private $xml;
    private $baseUrl;
    private $availableLocales = ['en', 'ar'];

    public function __construct()
    {
        $this->baseUrl = config('app.url');
        $this->initializeXml();
    }

    /**
     * Generate complete sitemap and save to public directory
     */
    public function generateSitemap(): bool
    {
        try {
            $startTime = microtime(true);
            
            // Add static pages
            $this->addStaticPages();
            
            // Add category pages
            $this->addCategoryPages();
            
            // Add product pages
            $this->addProductPages();
            
            // Save sitemap to public directory
            $sitemapContent = $this->xml->asXML();
            file_put_contents(public_path('sitemap.xml'), $sitemapContent);
            
            // Calculate generation time and file size
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            $fileSize = self::formatBytes(strlen($sitemapContent));
            $stats = $this->getSitemapStats();
            
            // Enhanced logging for automated generation
            \Log::info('[Sitemap Service] Sitemap generated successfully', [
                'urls_generated' => $stats['total_urls'],
                'static_pages' => $stats['static_pages'],
                'categories' => $stats['categories'],
                'products' => $stats['products'],
                'locales' => implode(', ', $stats['locales']),
                'file_size' => $fileSize,
                'execution_time_ms' => $executionTime,
                'generated_at' => now()->toDateTimeString()
            ]);
            
            return true;
        } catch (\Exception $e) {
            \Log::error('[Sitemap Service] Sitemap generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'failed_at' => now()->toDateTimeString()
            ]);
            return false;
        }
    }

    /**
     * Initialize XML structure with namespaces
     */
    private function initializeXml(): void
    {
        $this->xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset></urlset>');
        $this->xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $this->xml->addAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');
    }

    /**
     * Add static pages to sitemap
     */
    private function addStaticPages(): void
    {
        $staticPages = [
            ['route' => 'home', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['route' => 'categories.index', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['route' => 'products.index', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['route' => 'offers.index', 'priority' => '0.8', 'changefreq' => 'daily'],
        ];

        foreach ($staticPages as $page) {
            $this->addUrlWithAlternates($page['route'], [], $page['priority'], $page['changefreq']);
        }
    }

    /**
     * Add category pages to sitemap
     */
    private function addCategoryPages(): void
    {
        $categories = Category::active()->get();
        
        foreach ($categories as $category) {
            $this->addUrlWithAlternates(
                'categories.show',
                ['category' => $category->slug],
                '0.8',
                'weekly',
                $category->updated_at
            );
        }
    }

    /**
     * Add product pages to sitemap
     */
    private function addProductPages(): void
    {
        $products = Product::where('status', 'available')->get();
        
        foreach ($products as $product) {
            $this->addUrlWithAlternates(
                'products.show',
                ['product' => $product->slug],
                '0.7',
                'weekly',
                $product->updated_at
            );
        }
    }

    /**
     * Add URL with hreflang alternates for all locales
     */
    private function addUrlWithAlternates(
        string $route, 
        array $parameters = [], 
        string $priority = '0.5', 
        string $changefreq = 'monthly',
        $lastmod = null
    ): void {
        // Create one URL entry for the default locale (English)
        $url = $this->xml->addChild('url');
        
        // Generate default URL (English)
        $defaultUrl = $this->generateLocalizedUrl($route, $parameters, 'en');
        $url->addChild('loc', htmlspecialchars($defaultUrl));
        
        // Add lastmod if provided
        if ($lastmod) {
            $url->addChild('lastmod', Carbon::parse($lastmod)->toW3cString());
        } else {
            $url->addChild('lastmod', Carbon::now()->toW3cString());
        }
        
        $url->addChild('changefreq', $changefreq);
        $url->addChild('priority', $priority);
        
        // Add hreflang alternates for all locales
        foreach ($this->availableLocales as $altLocale) {
            $altUrl = $this->generateLocalizedUrl($route, $parameters, $altLocale);
            $link = $url->addChild('xhtml:link', '', 'http://www.w3.org/1999/xhtml');
            $link->addAttribute('rel', 'alternate');
            $link->addAttribute('hreflang', $altLocale);
            $link->addAttribute('href', htmlspecialchars($altUrl));
        }
        
        // Add x-default alternate (English)
        $defaultLink = $url->addChild('xhtml:link', '', 'http://www.w3.org/1999/xhtml');
        $defaultLink->addAttribute('rel', 'alternate');
        $defaultLink->addAttribute('hreflang', 'x-default');
        $defaultLink->addAttribute('href', htmlspecialchars($defaultUrl));
    }

    /**
     * Generate localized URL for given route and locale
     */
    private function generateLocalizedUrl(string $route, array $parameters, string $locale): string
    {
        // Temporarily set locale for URL generation
        $currentLocale = app()->getLocale();
        app()->setLocale($locale);
        
        try {
            $url = route($route, $parameters);
            
            // Add locale parameter if not default
            if ($locale !== config('app.locale')) {
                $url = $this->addLocaleToUrl($url, $locale);
            }
            
            return $url;
        } finally {
            // Restore original locale
            app()->setLocale($currentLocale);
        }
    }

    /**
     * Add locale parameter to URL
     */
    private function addLocaleToUrl(string $url, string $locale): string
    {
        $parsed = parse_url($url);
        $query = isset($parsed['query']) ? $parsed['query'] : '';
        
        parse_str($query, $queryParams);
        $queryParams['lang'] = $locale;
        
        $newQuery = http_build_query($queryParams);
        
        return $parsed['scheme'] . '://' . $parsed['host'] . 
               (isset($parsed['port']) ? ':' . $parsed['port'] : '') .
               $parsed['path'] . 
               ($newQuery ? '?' . $newQuery : '');
    }

    /**
     * Get sitemap statistics
     */
    public function getSitemapStats(): array
    {
        $categories = Category::active()->count();
        $products = Product::where('status', 'available')->count();
        $staticPages = 4; // home, categories, products, offers
        
        $totalUrls = $staticPages + $categories + $products;
        
        return [
            'static_pages' => $staticPages,
            'categories' => $categories,
            'products' => $products,
            'total_urls' => $totalUrls,
            'locales' => $this->availableLocales,
            'last_generated' => file_exists(public_path('sitemap.xml')) 
                ? Carbon::createFromTimestamp(filemtime(public_path('sitemap.xml')))
                : null
        ];
    }

    /**
     * Format bytes to human readable format
     */
    public static function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
