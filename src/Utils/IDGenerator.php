<?php

declare(strict_types=1);

namespace IGamingLib\Utils;

/**
 * Unique ID Generator
 * 
 * Provides methods to generate unique IDs for different purposes
 * (affiliate IDs, transaction IDs, etc).
 */
class IDGenerator
{
    /**
     * Generates unique alphanumeric ID
     * 
     * @param int $length ID length (default: 8)
     * @param string $prefix Optional prefix
     * @param bool $uppercase Whether should be uppercase (default: true)
     * @return string Generated ID
     * 
     * @example
     * $id = IDGenerator::generateUniqueId(8);
     * // Returns: "A3F9B2C1"
     */
    public static function generateUniqueId(int $length = 8, string $prefix = '', bool $uppercase = true): string
    {
        $id = '';
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $charLength = strlen($characters);

        for ($i = 0; $i < $length; $i++) {
            $id .= $characters[mt_rand(0, $charLength - 1)];
        }

        $id = $uppercase ? strtoupper($id) : $id;

        return $prefix . $id;
    }

    /**
     * Generates unique ID based on MD5 hash
     * 
     * @param int $length ID length (default: 8)
     * @param string $prefix Optional prefix
     * @return string Generated ID
     */
    public static function generateFromHash(int $length = 8, string $prefix = ''): string
    {
        $hash = md5(uniqid((string) mt_rand(), true));
        $id = strtoupper(substr($hash, 0, $length));
        
        return $prefix . $id;
    }

    /**
     * Generates unique numeric ID
     * 
     * @param int $length ID length
     * @param string $prefix Optional prefix
     * @return string Numeric ID
     */
    public static function generateNumericId(int $length = 8, string $prefix = ''): string
    {
        $min = pow(10, $length - 1);
        $max = pow(10, $length) - 1;
        $id = (string) mt_rand((int) $min, (int) $max);

        return $prefix . $id;
    }

    /**
     * Generates unique ID checking against list of existing IDs
     * 
     * @param callable $existsCallback Callback that checks if ID exists: fn(string $id): bool
     * @param int $length ID length
     * @param int $maxAttempts Maximum attempts (default: 100)
     * @return string Guaranteed unique ID
     * 
     * @throws \RuntimeException If cannot generate unique ID after attempts
     */
    public static function generateUniqueIdWithCheck(callable $existsCallback, int $length = 8, int $maxAttempts = 100): string
    {
        for ($i = 0; $i < $maxAttempts; $i++) {
            $id = self::generateUniqueId($length);
            if (!$existsCallback($id)) {
                return $id;
            }
        }

        throw new \RuntimeException("Could not generate unique ID after {$maxAttempts} attempts");
    }
}
