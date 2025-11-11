<?php

declare(strict_types=1);

namespace IGamingLib\Rollover;

/**
 * Rollover Calculator
 * 
 * Rollover is the requirement to wager a multiple of the deposited amount before
 * being able to withdraw. Example: if deposited $100 with 3x rollover, need to
 * wager $300 before being able to withdraw.
 */
class RolloverCalculator
{
    /**
     * Calculates required rollover based on total deposited and multiplier
     * 
     * @param float $totalDeposited Total deposited by user
     * @param float $multiplier Rollover multiplier (e.g., 1.5 = 150%, 3.0 = 300%)
     * @return float Required rollover
     * 
     * @example
     * // Deposited $1000 with 3x rollover
     * $required = $calculator->calculateRequired(1000.0, 3.0);
     * // Returns: 3000.0
     */
    public function calculateRequired(float $totalDeposited, float $multiplier): float
    {
        if ($totalDeposited < 0 || $multiplier < 0) {
            return 0.0;
        }

        return round($totalDeposited * $multiplier, 2);
    }

    /**
     * Calculates remaining rollover that still needs to be wagered
     * 
     * @param float $required Required rollover
     * @param float $totalBet Total already wagered by user
     * @return float Remaining rollover (never negative)
     * 
     * @example
     * // Needs to wager $3000, already wagered $800
     * $remaining = $calculator->calculateRemaining(3000.0, 800.0);
     * // Returns: 2200.0
     */
    public function calculateRemaining(float $required, float $totalBet): float
    {
        return max(0.0, round($required - $totalBet, 2));
    }

    /**
     * Checks if user can withdraw (rollover complete)
     * 
     * @param float $remaining Remaining rollover
     * @return bool True if can withdraw
     */
    public function canWithdraw(float $remaining): bool
    {
        return $remaining <= 0;
    }

    /**
     * Calculates rollover progress as percentage
     * 
     * @param float $required Required rollover
     * @param float $totalBet Total already wagered
     * @return float Progress from 0 to 100
     * 
     * @example
     * // Needs $3000, already wagered $1500
     * $progress = $calculator->calculateProgress(3000.0, 1500.0);
     * // Returns: 50.0 (50% complete)
     */
    public function calculateProgress(float $required, float $totalBet): float
    {
        if ($required <= 0) {
            return 100.0;
        }

        $progress = ($totalBet / $required) * 100.0;
        return round(min(100.0, max(0.0, $progress)), 2);
    }

    /**
     * Calculates everything at once (convenience method)
     * 
     * @param float $totalDeposited Total deposited
     * @param float $multiplier Rollover multiplier
     * @param float $totalBet Total already wagered
     * @return array Array with all calculations
     * 
     * @example
     * $result = $calculator->calculateAll(1000.0, 3.0, 800.0);
     * // Returns:
     * // [
     * //   'required' => 3000.0,
     * //   'remaining' => 2200.0,
     * //   'progress' => 26.67,
     * //   'can_withdraw' => false
     * // ]
     */
    public function calculateAll(float $totalDeposited, float $multiplier, float $totalBet): array
    {
        $required = $this->calculateRequired($totalDeposited, $multiplier);
        $remaining = $this->calculateRemaining($required, $totalBet);
        $progress = $this->calculateProgress($required, $totalBet);
        $canWithdraw = $this->canWithdraw($remaining);

        return [
            'required' => $required,
            'remaining' => $remaining,
            'progress' => $progress,
            'can_withdraw' => $canWithdraw,
        ];
    }
}
