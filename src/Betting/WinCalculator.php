<?php

declare(strict_types=1);

namespace IGamingLib\Betting;

/**
 * Win Calculator
 * 
 * Provides methods to calculate wins and losses in bets.
 */
class WinCalculator
{
    /**
     * Calculates win based on bet and multiplier
     * 
     * @param float $bet Bet amount
     * @param float $multiplier Multiplier (e.g., 2.5 = wins 2.5x the bet)
     * @return float Win amount
     * 
     * @example
     * // Bet $100 with 2.5x multiplier
     * $win = $calculator->calculateWin(100.0, 2.5);
     * // Returns: 250.0
     */
    public function calculateWin(float $bet, float $multiplier): float
    {
        if ($bet <= 0 || $multiplier < 0) {
            return 0.0;
        }

        return round($bet * $multiplier, 2);
    }

    /**
     * Calculates net win (win - bet)
     * 
     * @param float $bet Bet amount
     * @param float $multiplier Multiplier
     * @return float Net win
     * 
     * @example
     * // Bet $100, won $250
     * $netWin = $calculator->calculateNetWin(100.0, 2.5);
     * // Returns: 150.0 (250 - 100)
     */
    public function calculateNetWin(float $bet, float $multiplier): float
    {
        $totalWin = $this->calculateWin($bet, $multiplier);
        return round($totalWin - $bet, 2);
    }

    /**
     * Calculates win adjusted by RTP and probability
     * 
     * @param float $bet Bet amount
     * @param float $multiplier Base multiplier
     * @param float $rtp Target RTP (0-100)
     * @param float $probability Probability of winning (0-1)
     * @return float Adjusted win
     */
    public function calculateWinWithRTP(float $bet, float $multiplier, float $rtp, float $probability): float
    {
        if ($bet <= 0 || $multiplier < 0 || $rtp <= 0 || $probability <= 0) {
            return 0.0;
        }

        $rtpDecimal = $rtp / 100.0;
        $adjustedMultiplier = ($rtpDecimal / $probability) * $multiplier;
        
        return round($bet * $adjustedMultiplier, 2);
    }

    /**
     * Calculates loss (when player loses)
     * 
     * @param float $bet Bet amount
     * @return float Lost amount (equal to bet)
     */
    public function calculateLoss(float $bet): float
    {
        return max(0.0, round($bet, 2));
    }

    /**
     * Calculates ROI (Return on Investment) of a bet
     * 
     * @param float $bet Amount wagered
     * @param float $win Amount won
     * @return float ROI as percentage
     * 
     * @example
     * // Bet $100, won $150
     * $roi = $calculator->calculateROI(100.0, 150.0);
     * // Returns: 50.0 (50% return)
     */
    public function calculateROI(float $bet, float $win): float
    {
        if ($bet <= 0) {
            return 0.0;
        }

        $roi = (($win - $bet) / $bet) * 100.0;
        return round($roi, 2);
    }
}
