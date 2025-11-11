<?php

/**
 * IGamingLib Usage Examples
 * 
 * This file demonstrates how to use the library in different scenarios.
 */

require __DIR__ . '/../vendor/autoload.php';

use IGamingLib\RTP\RTPCalculator;
use IGamingLib\Rollover\RolloverCalculator;
use IGamingLib\Betting\BetValidator;
use IGamingLib\Betting\WinCalculator;
use IGamingLib\Random\WeightedRandom;
use IGamingLib\Utils\MoneyFormatter;
use IGamingLib\Utils\IDGenerator;
use IGamingLib\Utils\BrazilianValidator;

echo "=== IGamingLib - Usage Examples ===\n\n";

// ============================================
// RTP Calculator
// ============================================
echo "1. RTP Calculator\n";
echo str_repeat("-", 40) . "\n";

$rtpCalc = new RTPCalculator();

// Calculate win based on RTP
$win = $rtpCalc->calculateWinFromRTP(100.0, 95.0, 1/3);
echo "Win with RTP 95% and probability 33%: $" . number_format($win, 2, '.', ',') . "\n";

// Calculate adjusted multiplier
$multiplier = $rtpCalc->calculateAdjustedMultiplier(95.0, 1/3);
echo "Adjusted multiplier: " . $multiplier . "x\n";

// Calculate House Edge
$houseEdge = $rtpCalc->calculateHouseEdge(95.0);
echo "House Edge: " . $houseEdge . "%\n\n";

// ============================================
// Rollover Calculator
// ============================================
echo "2. Rollover Calculator\n";
echo str_repeat("-", 40) . "\n";

$rolloverCalc = new RolloverCalculator();

// Calculate required rollover
$required = $rolloverCalc->calculateRequired(1000.0, 3.0);
echo "Required rollover (deposit $1000, 3x): $" . number_format($required, 2, '.', ',') . "\n";

// Calculate remaining rollover
$remaining = $rolloverCalc->calculateRemaining($required, 2500.0);
echo "Remaining rollover (already wagered $2500): $" . number_format($remaining, 2, '.', ',') . "\n";

// Check if can withdraw
$canWithdraw = $rolloverCalc->canWithdraw($remaining);
echo "Can withdraw: " . ($canWithdraw ? "Yes" : "No") . "\n";

// Calculate progress
$progress = $rolloverCalc->calculateProgress($required, 2500.0);
echo "Progress: " . $progress . "%\n\n";

// ============================================
// Bet Validator
// ============================================
echo "3. Bet Validator\n";
echo str_repeat("-", 40) . "\n";

$betValidator = new BetValidator();

// Validate bet amount
$result = $betValidator->validateAmount(50.0, 10.0, 1000.0);
echo "Bet validation $50 (min $10, max $1000): " . ($result->isValid() ? "Valid" : $result->getMessage()) . "\n";

// Validate balance
$balances = ['main' => 100.0, 'bonus' => 50.0];
$result = $betValidator->validateBalance(120.0, $balances);
echo "Balance validation $120: " . ($result->isValid() ? "Valid" : $result->getMessage()) . "\n";

// Calculate distribution
$distribution = $betValidator->calculateBalanceDistribution(120.0, $balances);
echo "Distribution of $120: ";
print_r($distribution);
echo "\n";

// ============================================
// Win Calculator
// ============================================
echo "4. Win Calculator\n";
echo str_repeat("-", 40) . "\n";

$winCalc = new WinCalculator();

// Calculate win
$win = $winCalc->calculateWin(100.0, 2.5);
echo "Win (bet $100, multiplier 2.5x): $" . number_format($win, 2, '.', ',') . "\n";

// Calculate net win
$netWin = $winCalc->calculateNetWin(100.0, 2.5);
echo "Net win: $" . number_format($netWin, 2, '.', ',') . "\n";

// Calculate ROI
$roi = $winCalc->calculateROI(100.0, 150.0);
echo "ROI (bet $100, won $150): " . $roi . "%\n\n";

// ============================================
// Weighted Random
// ============================================
echo "5. Weighted Random\n";
echo str_repeat("-", 40) . "\n";

$random = new WeightedRandom();

// Generate result based on weights
$weights = [10, 30, 50, 10]; // 4 outcomes
$index = $random->generate($weights);
echo "Generated result (weights [10, 30, 50, 10]): Index " . $index . "\n";

// Calculate probabilities
$probabilities = $random->calculateProbability($weights);
echo "Probabilities: " . implode('%, ', $probabilities) . "%\n\n";

// ============================================
// Utils
// ============================================
echo "6. Utils\n";
echo str_repeat("-", 40) . "\n";

// Format money
$formatted = MoneyFormatter::format(1234.56);
echo "Money formatting: " . $formatted . "\n";

// Generate unique ID
$id = IDGenerator::generateUniqueId(8);
echo "Generated unique ID: " . $id . "\n";

// Validate CPF
$cpf = '12345678909';
$isValid = BrazilianValidator::validateCPF($cpf);
echo "CPF valid ($cpf): " . ($isValid ? "Yes" : "No") . "\n";

$formattedCPF = BrazilianValidator::formatCPF($cpf);
echo "Formatted CPF: " . $formattedCPF . "\n";

echo "\n=== End of Examples ===\n";
