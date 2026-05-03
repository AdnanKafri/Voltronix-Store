<?php

namespace App\Support;

class MoneyFormatter
{
    /**
     * Format a monetary amount using a stored currency snapshot.
     */
    public static function formatSnapshot(
        mixed $amount,
        string $currencyCode = 'USD',
        ?string $symbol = null,
        float $rate = 1.0,
        ?string $locale = null,
        bool $showSymbol = true,
        bool $alreadyConverted = false
    ): string {
        $locale = $locale ?: app()->getLocale();
        $normalized = self::normalize($amount);
        $converted = $alreadyConverted ? $normalized : self::round($normalized * max($rate, 0.00000001));
        $formattedNumber = self::formatNumber($converted, $locale);

        if (!$showSymbol) {
            return $formattedNumber;
        }

        $symbol = $symbol ?: $currencyCode;

        return self::applySymbolPlacement($formattedNumber, $symbol, $locale);
    }

    /**
     * Round money consistently to 2 decimals.
     */
    public static function round(mixed $amount): float
    {
        return round(self::normalize($amount), 2);
    }

    /**
     * Normalize mixed numeric input to float.
     */
    public static function normalize(mixed $amount): float
    {
        if ($amount === null || $amount === '' || !is_numeric($amount)) {
            return 0.0;
        }

        return (float) $amount;
    }

    private static function formatNumber(float $amount, string $locale): string
    {
        if ($locale === 'ar') {
            return number_format($amount, 2, '٫', '٬');
        }

        return number_format($amount, 2, '.', ',');
    }

    private static function applySymbolPlacement(string $formattedNumber, string $symbol, string $locale): string
    {
        $prefixSymbols = ['$', '€', '£', '¥'];

        if ($locale === 'ar') {
            return $formattedNumber . "\u{00A0}" . $symbol;
        }

        if (in_array($symbol, $prefixSymbols, true)) {
            return $symbol . $formattedNumber;
        }

        return $formattedNumber . "\u{00A0}" . $symbol;
    }
}
