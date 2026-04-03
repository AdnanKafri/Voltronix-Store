<?php

namespace App\Services;

use App\Models\Currency;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class CurrencyUpdateService
{
    /**
     * API endpoint for exchange rates
     */
    private const API_URL = 'https://api.exchangerate.host/live';
    
    /**
     * API access key
     */
    private const API_KEY = 'eeb8aeb66dd7da0ac8bf9f29a5610331';
    
    /**
     * Cache duration in seconds (24 hours)
     */
    private const CACHE_DURATION = 86400;
    
    /**
     * Maximum retry attempts
     */
    private const MAX_RETRIES = 3;
    
    /**
     * Retry delay in milliseconds
     */
    private const RETRY_DELAY = 1000;

    /**
     * Update all currency rates from the API
     *
     * @return array ['success' => bool, 'updated' => int, 'message' => string, 'errors' => array]
     */
    public function updateAllRates(): array
    {
        try {
            // Check cache first to avoid redundant API calls
            $cacheKey = 'currency_rates_last_update';
            $lastUpdate = Cache::get($cacheKey);
            
            if ($lastUpdate && now()->diffInHours($lastUpdate) < 24) {
                Log::info('Currency rates already updated recently', [
                    'last_update' => $lastUpdate,
                    'hours_ago' => now()->diffInHours($lastUpdate)
                ]);
                
                return [
                    'success' => true,
                    'updated' => 0,
                    'message' => 'Currency rates were already updated within the last 24 hours.',
                    'errors' => [],
                    'cached' => true
                ];
            }

            // Fetch rates from API with retry logic
            $rates = $this->fetchRatesWithRetry();
            
            if (empty($rates)) {
                throw new Exception('No exchange rates received from API');
            }

            // Update currencies in database
            $result = $this->updateCurrenciesInDatabase($rates);
            
            // Cache the update timestamp
            Cache::put($cacheKey, now(), self::CACHE_DURATION);
            
            // Clear currency cache
            CurrencyService::clearCache();
            
            Log::info('Currency rates updated successfully', [
                'updated_count' => $result['updated'],
                'total_rates' => count($rates),
                'timestamp' => now()
            ]);
            
            return $result;
            
        } catch (Exception $e) {
            Log::error('Currency update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'updated' => 0,
                'message' => 'Failed to update currency rates: ' . $e->getMessage(),
                'errors' => [$e->getMessage()]
            ];
        }
    }

    /**
     * Fetch exchange rates from API with retry logic
     *
     * @return array
     * @throws Exception
     */
    private function fetchRatesWithRetry(): array
    {
        $attempt = 0;
        $lastException = null;

        while ($attempt < self::MAX_RETRIES) {
            try {
                $attempt++;
                
                Log::info("Fetching currency rates (attempt {$attempt}/{" . self::MAX_RETRIES . "})");
                
                $response = Http::timeout(10)
                    ->retry(2, 500)
                    ->get(self::API_URL, [
                        'access_key' => self::API_KEY
                    ]);

                if (!$response->successful()) {
                    throw new Exception("API request failed with status: {$response->status()}");
                }

                $data = $response->json();
                
                // Validate response structure
                if (!isset($data['success']) || !$data['success']) {
                    $errorMessage = $data['error']['info'] ?? 'Unknown API error';
                    throw new Exception("API returned error: {$errorMessage}");
                }
                
                if (!isset($data['quotes']) || !is_array($data['quotes'])) {
                    throw new Exception('Invalid API response structure: missing quotes');
                }
                
                // Extract rates (remove USD prefix from keys)
                $rates = [];
                foreach ($data['quotes'] as $key => $value) {
                    // Convert USDEUR to EUR, USDAED to AED, etc.
                    $currencyCode = str_replace('USD', '', $key);
                    if (!empty($currencyCode) && is_numeric($value) && $value > 0) {
                        $rates[$currencyCode] = (float) $value;
                    }
                }
                
                // Add USD as base currency with rate 1.0
                $rates['USD'] = 1.0;
                
                Log::info('Successfully fetched currency rates', [
                    'count' => count($rates),
                    'attempt' => $attempt
                ]);
                
                return $rates;
                
            } catch (Exception $e) {
                $lastException = $e;
                
                Log::warning("Currency fetch attempt {$attempt} failed", [
                    'error' => $e->getMessage(),
                    'attempt' => $attempt
                ]);
                
                if ($attempt < self::MAX_RETRIES) {
                    usleep(self::RETRY_DELAY * 1000 * $attempt); // Exponential backoff
                }
            }
        }

        throw new Exception(
            "Failed to fetch currency rates after " . self::MAX_RETRIES . " attempts: " . 
            ($lastException ? $lastException->getMessage() : 'Unknown error')
        );
    }

    /**
     * Update currencies in database with fetched rates
     *
     * @param array $rates
     * @return array
     */
    private function updateCurrenciesInDatabase(array $rates): array
    {
        $updated = 0;
        $errors = [];
        $skipped = [];
        
        // Get all active currencies from database
        $currencies = Currency::all();
        
        foreach ($currencies as $currency) {
            try {
                // Skip if currency code not in API response
                if (!isset($rates[$currency->code])) {
                    $skipped[] = $currency->code;
                    Log::info("Currency {$currency->code} not found in API response, skipping");
                    continue;
                }
                
                $newRate = $rates[$currency->code];
                
                // Skip USD (base currency) - keep it at 1.0
                if ($currency->code === 'USD') {
                    if ($currency->exchange_rate != 1.0) {
                        $currency->update([
                            'exchange_rate' => 1.0,
                            'last_updated_at' => now()
                        ]);
                        $updated++;
                    }
                    continue;
                }
                
                // Update currency rate
                $currency->update([
                    'exchange_rate' => $newRate,
                    'last_updated_at' => now()
                ]);
                
                $updated++;
                
                Log::info("Updated currency {$currency->code}", [
                    'old_rate' => $currency->exchange_rate,
                    'new_rate' => $newRate
                ]);
                
            } catch (Exception $e) {
                $errors[] = "Failed to update {$currency->code}: {$e->getMessage()}";
                Log::error("Failed to update currency {$currency->code}", [
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        $message = "Successfully updated {$updated} " . Str::plural('currency', $updated);
        if (!empty($skipped)) {
            $message .= ". Skipped: " . implode(', ', $skipped);
        }
        
        return [
            'success' => true,
            'updated' => $updated,
            'skipped' => $skipped,
            'message' => $message,
            'errors' => $errors
        ];
    }

    /**
     * Force clear the update cache
     *
     * @return void
     */
    public function clearUpdateCache(): void
    {
        Cache::forget('currency_rates_last_update');
        Log::info('Currency update cache cleared');
    }

    /**
     * Get last update timestamp
     *
     * @return \Carbon\Carbon|null
     */
    public function getLastUpdateTime(): ?\Carbon\Carbon
    {
        return Cache::get('currency_rates_last_update');
    }

    /**
     * Check if update is needed (more than 24 hours since last update)
     *
     * @return bool
     */
    public function isUpdateNeeded(): bool
    {
        $lastUpdate = $this->getLastUpdateTime();
        
        if (!$lastUpdate) {
            return true;
        }
        
        return now()->diffInHours($lastUpdate) >= 24;
    }
}
