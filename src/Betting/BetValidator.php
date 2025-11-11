<?php

declare(strict_types=1);

namespace IGamingLib\Betting;

/**
 * Bet Validator
 * 
 * Provides common validations for bets in iGaming games.
 */
class BetValidator
{
    /**
     * Validates bet amount against minimum and maximum limits
     * 
     * @param float $bet Bet amount
     * @param float $min Minimum allowed amount
     * @param float $max Maximum allowed amount
     * @return ValidationResult Validation result
     * 
     * @example
     * $result = $validator->validateAmount(50.0, 10.0, 1000.0);
     * if ($result->isValid()) {
     *     // Bet is valid
     * }
     */
    public function validateAmount(float $bet, float $min, float $max): ValidationResult
    {
        if ($bet < $min) {
            return ValidationResult::invalid("Minimum bet is $" . number_format($min, 2, '.', ','));
        }

        if ($bet > $max) {
            return ValidationResult::invalid("Maximum bet is $" . number_format($max, 2, '.', ','));
        }

        if ($bet <= 0) {
            return ValidationResult::invalid("Bet must be greater than zero");
        }

        return ValidationResult::valid();
    }

    /**
     * Validates if there is sufficient balance for the bet
     * 
     * @param float $bet Bet amount
     * @param array $balances Array of balances ['main' => 100.0, 'bonus' => 50.0]
     * @return ValidationResult Validation result
     * 
     * @example
     * $balances = ['main' => 100.0, 'bonus' => 50.0];
     * $result = $validator->validateBalance(120.0, $balances);
     */
    public function validateBalance(float $bet, array $balances): ValidationResult
    {
        $totalBalance = array_sum($balances);

        if ($bet > $totalBalance) {
            return ValidationResult::invalid("Insufficient balance. Available: $" . number_format($totalBalance, 2, '.', ','));
        }

        return ValidationResult::valid();
    }

    /**
     * Calculates how to distribute bet across different balance types
     * 
     * Priority: uses main balance first, then bonus, then others
     * 
     * @param float $bet Bet amount
     * @param array $balances Array of balances ['main' => 100.0, 'bonus' => 50.0]
     * @return array Distribution ['main' => 100.0, 'bonus' => 20.0]
     * 
     * @example
     * $balances = ['main' => 100.0, 'bonus' => 50.0];
     * $distribution = $validator->calculateBalanceDistribution(120.0, $balances);
     * // Returns: ['main' => 100.0, 'bonus' => 20.0]
     */
    public function calculateBalanceDistribution(float $bet, array $balances): array
    {
        $distribution = [];
        $remaining = $bet;

        // Priority order: main, bonus, others
        $priority = ['main', 'bonus', 'ref', 'affiliate'];
        
        foreach ($priority as $type) {
            if ($remaining <= 0) {
                break;
            }

            $available = $balances[$type] ?? 0.0;
            if ($available > 0) {
                $used = min($remaining, $available);
                $distribution[$type] = round($used, 2);
                $remaining -= $used;
            }
        }

        // Process other balance types not in priority list
        foreach ($balances as $type => $amount) {
            if ($remaining <= 0) {
                break;
            }

            if (!in_array($type, $priority) && !isset($distribution[$type])) {
                $used = min($remaining, $amount);
                $distribution[$type] = round($used, 2);
                $remaining -= $used;
            }
        }

        return $distribution;
    }

    /**
     * Validates complete bet (amount + balance)
     * 
     * @param float $bet Bet amount
     * @param float $min Minimum amount
     * @param float $max Maximum amount
     * @param array $balances Available balances
     * @return ValidationResult Validation result
     */
    public function validate(float $bet, float $min, float $max, array $balances): ValidationResult
    {
        $amountResult = $this->validateAmount($bet, $min, $max);
        if (!$amountResult->isValid()) {
            return $amountResult;
        }

        return $this->validateBalance($bet, $balances);
    }
}
