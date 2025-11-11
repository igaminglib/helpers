<?php

declare(strict_types=1);

namespace IGamingLib\RTP;

/**
 * RTP (Return to Player) Calculator
 * 
 * Provides methods for RTP-related calculations in iGaming games.
 * RTP is expressed as a percentage (0-100), where 95% means the player
 * receives on average $95 for every $100 wagered.
 */
class RTPCalculator
{
    /**
     * Calculates adjusted win based on RTP and hit probability
     * 
     * Formula: adjusted_multiplier = (RTP / 100) / hit_probability
     * 
     * @param float $bet Bet amount
     * @param float $rtp Target RTP (0-100)
     * @param float $hitProbability Hit probability (0-1), e.g., 0.33 for 33%
     * @return float Adjusted win amount
     * 
     * @example
     * // RTP 95%, probability 33.33% (1 in 3)
     * $win = $calculator->calculateWinFromRTP(100.0, 95.0, 1/3);
     * // Returns: 285.0 (wins 2.85x the bet)
     */
    public function calculateWinFromRTP(float $bet, float $rtp, float $hitProbability): float
    {
        if ($bet <= 0 || $rtp <= 0 || $hitProbability <= 0) {
            return 0.0;
        }

        $rtpDecimal = $rtp / 100.0;
        $adjustedMultiplier = $rtpDecimal / $hitProbability;
        
        return round($bet * $adjustedMultiplier, 2);
    }

    /**
     * Calculates adjusted multiplier needed to achieve target RTP
     * 
     * @param float $rtp Target RTP (0-100)
     * @param float $hitProbability Hit probability (0-1)
     * @return float Adjusted multiplier
     * 
     * @example
     * // RTP 95%, 1 in 3 chance
     * $multiplier = $calculator->calculateAdjustedMultiplier(95.0, 1/3);
     * // Returns: 2.85
     */
    public function calculateAdjustedMultiplier(float $rtp, float $hitProbability): float
    {
        if ($rtp <= 0 || $hitProbability <= 0) {
            return 0.0;
        }

        $rtpDecimal = $rtp / 100.0;
        return round($rtpDecimal / $hitProbability, 4);
    }

    /**
     * Calculates House Edge (house advantage)
     * 
     * House Edge = 100 - RTP
     * 
     * @param float $rtp RTP (0-100)
     * @return float House Edge as percentage
     * 
     * @example
     * $houseEdge = $calculator->calculateHouseEdge(95.0);
     * // Returns: 5.0 (house has 5% advantage)
     */
    public function calculateHouseEdge(float $rtp): float
    {
        return max(0.0, 100.0 - $rtp);
    }

    /**
     * Validates if RTP is within valid range
     * 
     * @param float $rtp RTP to validate
     * @param float $min Minimum allowed RTP (default: 0)
     * @param float $max Maximum allowed RTP (default: 100)
     * @return bool True if valid
     */
    public function validateRTPRange(float $rtp, float $min = 0.0, float $max = 100.0): bool
    {
        return $rtp >= $min && $rtp <= $max;
    }

    /**
     * Normalizes RTP to valid range (0-100)
     * 
     * @param float $rtp RTP to normalize
     * @return float Normalized RTP between 0 and 100
     */
    public function normalizeRTP(float $rtp): float
    {
        return max(0.0, min(100.0, $rtp));
    }

    /**
     * Calculates effective RTP based on wins and bets
     * 
     * @param float $totalWins Total won by players
     * @param float $totalBets Total wagered by players
     * @return float Effective RTP as percentage
     * 
     * @example
     * // Players wagered $1000 and won $950
     * $rtp = $calculator->calculateEffectiveRTP(950.0, 1000.0);
     * // Returns: 95.0
     */
    public function calculateEffectiveRTP(float $totalWins, float $totalBets): float
    {
        if ($totalBets <= 0) {
            return 0.0;
        }

        return round(($totalWins / $totalBets) * 100.0, 2);
    }
}
