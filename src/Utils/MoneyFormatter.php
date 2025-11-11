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
     * $formatted = MoneyFormatter::format(1234.56, 2, 'pt_BR');
     * // Returns: "R$ 1.234,56"
     */
    public static function format(float $amount, int $decimals = 2, string $locale = 'pt_BR'): string
    {
        return match($locale) {
            'pt_BR' => self::formatBrazilian($amount, $decimals),
            'en_US', 'USD' => self::formatDollar($amount, $decimals),
            'en_GB', 'GBP' => self::formatPound($amount, $decimals),
            'de_DE', 'EUR', 'fr_FR', 'es_ES', 'it_IT' => self::formatEuro($amount, $decimals),
            'en_IN', 'INR' => self::formatRupee($amount, $decimals),
            'zh_CN', 'CNY' => self::formatYuan($amount, $decimals),
            'ko_KR', 'KRW' => self::formatWon($amount, $decimals),
            default => self::formatInternational($amount, $decimals),
        };
    }

    /**
     * Formats in Brazilian standard (R$ 1.234,56)
     */
    public static function formatBrazilian(float $amount, int $decimals = 2): string
    {
        return 'R$ ' . number_format($amount, $decimals, ',', '.');
    }

    /**
     * Formats in US Dollar standard ($1,234.56)
     */
    public static function formatDollar(float $amount, int $decimals = 2): string
    {
        return '$' . number_format($amount, $decimals, '.', ',');
    }

    /**
     * Formats in British Pound standard (£1,234.56)
     */
    public static function formatPound(float $amount, int $decimals = 2): string
    {
        return '£' . number_format($amount, $decimals, '.', ',');
    }

    /**
     * Formats in Euro standard (€1.234,56)
     */
    public static function formatEuro(float $amount, int $decimals = 2): string
    {
        return '€' . number_format($amount, $decimals, ',', '.');
    }

    /**
     * Formats in Indian Rupee standard (₹1,234.56)
     */
    public static function formatRupee(float $amount, int $decimals = 2): string
    {
        return '₹' . number_format($amount, $decimals, '.', ',');
    }

    /**
     * Formats in Chinese Yuan standard (¥1,234.56)
     */
    public static function formatYuan(float $amount, int $decimals = 2): string
    {
        return '¥' . number_format($amount, $decimals, '.', ',');
    }

    /**
     * Formats in South Korean Won standard (₩1,234)
     * Note: Won typically doesn't use decimals
     */
    public static function formatWon(float $amount, int $decimals = 0): string
    {
        return '₩' . number_format($amount, $decimals, '.', ',');
    }

    /**
     * Formats in international standard ($1,234.56)
     * 
     * @param string $symbol Currency symbol (default: $)
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
        return match($locale) {
            'pt_BR', 'de_DE', 'EUR', 'fr_FR', 'es_ES', 'it_IT' => number_format($amount, $decimals, ',', '.'),
            default => number_format($amount, $decimals, '.', ','),
        };
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
