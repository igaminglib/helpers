<?php

declare(strict_types=1);

namespace IGamingLib\Utils;

/**
 * Brazilian Validator
 * 
 * Provides validations for Brazilian documents (CPF, CNPJ).
 * CPF is the Brazilian individual taxpayer registry identification.
 * CNPJ is the Brazilian corporate taxpayer registry identification.
 */
class BrazilianValidator
{
    /**
     * Validates CPF (Brazilian individual tax ID)
     * 
     * @param string $cpf CPF to validate (with or without formatting)
     * @return bool True if valid
     * 
     * @example
     * $isValid = BrazilianValidator::validateCPF('123.456.789-09');
     */
    public static function validateCPF(string $cpf): bool
    {
        // Remove formatting
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        // Check length
        if (strlen($cpf) !== 11) {
            return false;
        }

        // Check if all digits are the same
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Validate first check digit
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (int) $cpf[$i] * (10 - $i);
        }
        $remainder = $sum % 11;
        $digit1 = $remainder < 2 ? 0 : 11 - $remainder;

        if ((int) $cpf[9] !== $digit1) {
            return false;
        }

        // Validate second check digit
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += (int) $cpf[$i] * (11 - $i);
        }
        $remainder = $sum % 11;
        $digit2 = $remainder < 2 ? 0 : 11 - $remainder;

        return (int) $cpf[10] === $digit2;
    }

    /**
     * Validates CNPJ (Brazilian corporate tax ID)
     * 
     * @param string $cnpj CNPJ to validate (with or without formatting)
     * @return bool True if valid
     */
    public static function validateCNPJ(string $cnpj): bool
    {
        // Remove formatting
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        // Check length
        if (strlen($cnpj) !== 14) {
            return false;
        }

        // Check if all digits are the same
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        // Validate first check digit
        $weights = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += (int) $cnpj[$i] * $weights[$i];
        }
        $remainder = $sum % 11;
        $digit1 = $remainder < 2 ? 0 : 11 - $remainder;

        if ((int) $cnpj[12] !== $digit1) {
            return false;
        }

        // Validate second check digit
        $weights = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $sum = 0;
        for ($i = 0; $i < 13; $i++) {
            $sum += (int) $cnpj[$i] * $weights[$i];
        }
        $remainder = $sum % 11;
        $digit2 = $remainder < 2 ? 0 : 11 - $remainder;

        return (int) $cnpj[13] === $digit2;
    }

    /**
     * Formats CPF (123.456.789-09)
     */
    public static function formatCPF(string $cpf): string
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        if (strlen($cpf) !== 11) {
            return $cpf;
        }
        return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
    }

    /**
     * Formats CNPJ (12.345.678/0001-90)
     */
    public static function formatCNPJ(string $cnpj): string
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        if (strlen($cnpj) !== 14) {
            return $cnpj;
        }
        return substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.' . substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) . '-' . substr($cnpj, 12, 2);
    }
}
