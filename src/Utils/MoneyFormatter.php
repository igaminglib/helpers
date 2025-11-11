<?php

declare(strict_types=1);

namespace IGamingLib\Utils;

/**
 * Money Formatter
 * 
 * Provides methods to format monetary values in different formats.
 */
class MoneyFormatter
{
    /**
     * Formats monetary value
     * 
     * @param float $amount Amount to format
     * @param int $decimals Decimal places (default: 2)
     * @param string $locale Locale for formatting (default: pt_BR)
     * @return string Formatted value
     * 
     * @example
     * $formatted = MoneyFormatter::format(1234.56);
     * // Returns: "R$ 1.234,56" (Brazilian format)
     */
    public static function format(float $amount, int $decimals = 2, string $locale = 'pt_BR'): string
    {
        if ($locale === 'pt_BR') {
            return self::formatBrazilian($amount, $decimals);
        }

        // Fallback to international format
        return number_format($amount, $decimals, '.', ',');
    }

    /**
     * Formats in Brazilian standard (R$ 1.234,56)
     */
    public static function formatBrazilian(float $amount, int $decimals = 2): string
    {
        return 'R$ ' . number_format($amount, $decimals, ',', '.');
    }

    /**
     * Formats in international standard ($1,234.56)
     */
    public static function formatInternational(float $amount, int $decimals = 2, string $symbol = '$'): string
    {
        return $symbol . number_format($amount, $decimals, '.', ',');
    }

    /**
     * Formats number only (without symbol)
     */
    public static function formatNumber(float $amount, int $decimals = 2, string $locale = 'pt_BR'): string
    {
        if ($locale === 'pt_BR') {
            return number_format($amount, $decimals, ',', '.');
        }

        return number_format($amount, $decimals, '.', ',');
    }

    /**
     * Formats compact value (e.g., 1.2K, 1.5M)
     */
    public static function formatCompact(float $amount, int $decimals = 1): string
    {
        $abs = abs($amount);
        $sign = $amount < 0 ? '-' : '';

        if ($abs >= 1000000) {
            return $sign . number_format($abs / 1000000, $decimals, ',', '.') . 'M';
        }

        if ($abs >= 1000) {
            return $sign . number_format($abs / 1000, $decimals, ',', '.') . 'K';
        }

        return $sign . number_format($abs, $decimals, ',', '.');
    }
}
