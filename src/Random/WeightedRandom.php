<?php

declare(strict_types=1);

namespace IGamingLib\Random;

/**
 * Weighted Random Number Generator
 * 
 * Generates random results based on weights/probabilities.
 * Useful for creating game outcomes with specific probabilities.
 */
class WeightedRandom
{
    /**
     * Generates random index based on weights
     * 
     * @param array $weights Array of weights [10, 30, 50, 10]
     * @return int Result index (0-based)
     * 
     * @example
     * $weights = [10, 30, 50, 10]; // 4 outcomes with different weights
     * $index = $generator->generate($weights);
     * // Returns: 0, 1, 2, or 3 based on weights
     */
    public function generate(array $weights): int
    {
        if (empty($weights)) {
            throw new \InvalidArgumentException('Weights array cannot be empty');
        }

        $totalWeight = array_sum($weights);
        if ($totalWeight <= 0) {
            throw new \InvalidArgumentException('Sum of weights must be greater than zero');
        }

        $random = mt_rand(1, (int) $totalWeight);
        $current = 0;

        foreach ($weights as $index => $weight) {
            $current += $weight;
            if ($random <= $current) {
                return $index;
            }
        }

        // Fallback (should not happen)
        return array_key_last($weights);
    }

    /**
     * Calculates probabilities of each outcome based on weights
     * 
     * @param array $weights Array of weights
     * @return array Array of probabilities as percentage
     * 
     * @example
     * $weights = [10, 30, 50, 10];
     * $probabilities = $generator->calculateProbability($weights);
     * // Returns: [10.0, 30.0, 50.0, 10.0]
     */
    public function calculateProbability(array $weights): array
    {
        $totalWeight = array_sum($weights);
        
        if ($totalWeight <= 0) {
            return array_fill(0, count($weights), 0.0);
        }

        $probabilities = [];
        foreach ($weights as $weight) {
            $probabilities[] = round(($weight / $totalWeight) * 100.0, 2);
        }

        return $probabilities;
    }

    /**
     * Generates multiple random results
     * 
     * @param array $weights Array of weights
     * @param int $count Number of results
     * @return array Array of generated indices
     */
    public function generateMultiple(array $weights, int $count): array
    {
        $results = [];
        for ($i = 0; $i < $count; $i++) {
            $results[] = $this->generate($weights);
        }
        return $results;
    }

    /**
     * Generates result with specific seed (useful for testing)
     * 
     * @param array $weights Array of weights
     * @param int $seed Seed for randomization
     * @return int Result index
     */
    public function generateWithSeed(array $weights, int $seed): int
    {
        mt_srand($seed);
        $result = $this->generate($weights);
        mt_srand(); // Restore random seed
        return $result;
    }
}
