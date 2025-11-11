# IGamingLib

Framework-agnostic PHP library for common iGaming functionalities (RTP, Rollover, Validations, etc).

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.2-blue.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

## 🎯 Features

- ✅ **Framework-agnostic** - Works with pure PHP, Laravel, Symfony, CodeIgniter, etc
- ✅ **Zero dependencies** - Only PHP 8.2+ required
- ✅ **PSR-4** - Standard structure and autoloading
- ✅ **Type-safe** - Strict types and complete documentation
- ✅ **Well tested** - Full test coverage

## 📦 Installation

### Install directly from GitHub

Add the repository to your project's `composer.json`:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/your-username/igaming-lib"
        }
    ],
    "require": {
        "your-vendor/igaming-lib": "dev-main"
    }
}
```

Then run:
```bash
composer update
```

Or install directly:
```bash
composer require your-vendor/igaming-lib:dev-main --repository '{"type":"vcs","url":"https://github.com/your-username/igaming-lib"}'
```

**Important:** 
- Replace `your-username` with your GitHub username
- Replace `your-vendor` with your desired vendor name (must match the `name` in this library's `composer.json`)
- Use `dev-main` for the main branch, or specify a tag like `v1.0.0`

For detailed installation instructions, see [INSTALLATION.md](INSTALLATION.md).

## 🚀 Quick Start

### RTP Calculator

```php
use IGamingLib\RTP\RTPCalculator;

$calculator = new RTPCalculator();

// Calculate win based on RTP
$win = $calculator->calculateWinFromRTP(
    bet: 100.0,           // Bet of $100
    rtp: 95.0,            // RTP of 95%
    hitProbability: 1/3   // Probability of 33.33% (1 in 3)
);
// Returns: 285.0 (wins $285)

// Calculate House Edge
$houseEdge = $calculator->calculateHouseEdge(95.0);
// Returns: 5.0 (house has 5% advantage)
```

### Rollover Calculator

```php
use IGamingLib\Rollover\RolloverCalculator;

$calculator = new RolloverCalculator();

// Calculate required rollover
$required = $calculator->calculateRequired(
    totalDeposited: 1000.0,  // Deposited $1000
    multiplier: 3.0          // 3x rollover
);
// Returns: 3000.0 (needs to bet $3000)

// Check if can withdraw
$remaining = $calculator->calculateRemaining(3000.0, 2500.0);
$canWithdraw = $calculator->canWithdraw($remaining);
// Returns: false (still needs $500)

// Calculate everything at once
$result = $calculator->calculateAll(1000.0, 3.0, 2500.0);
// Returns:
// [
//   'required' => 3000.0,
//   'remaining' => 500.0,
//   'progress' => 83.33,
//   'can_withdraw' => false
// ]
```

### Bet Validator

```php
use IGamingLib\Betting\BetValidator;

$validator = new BetValidator();

// Validate bet amount
$result = $validator->validateAmount(50.0, 10.0, 1000.0);
if ($result->isValid()) {
    // Bet is valid
} else {
    echo $result->getMessage();
}

// Validate balance
$balances = ['main' => 100.0, 'bonus' => 50.0];
$result = $validator->validateBalance(120.0, $balances);
if ($result->isValid()) {
    // Has sufficient balance
}

// Calculate balance distribution
$distribution = $validator->calculateBalanceDistribution(120.0, $balances);
// Returns: ['main' => 100.0, 'bonus' => 20.0]
```

### Win Calculator

```php
use IGamingLib\Betting\WinCalculator;

$calculator = new WinCalculator();

// Calculate win
$win = $calculator->calculateWin(100.0, 2.5);
// Returns: 250.0

// Calculate net win
$netWin = $calculator->calculateNetWin(100.0, 2.5);
// Returns: 150.0 (250 - 100)

// Calculate ROI
$roi = $calculator->calculateROI(100.0, 150.0);
// Returns: 50.0 (50% return)
```

### Weighted Random

```php
use IGamingLib\Random\WeightedRandom;

$generator = new WeightedRandom();

// Generate result based on weights
$weights = [10, 30, 50, 10]; // 4 outcomes with different probabilities
$index = $generator->generate($weights);
// Returns: 0, 1, 2, or 3 based on weights

// Calculate probabilities
$probabilities = $generator->calculateProbability($weights);
// Returns: [10.0, 30.0, 50.0, 10.0] (in percentage)
```

### Utils

```php
use IGamingLib\Utils\MoneyFormatter;
use IGamingLib\Utils\IDGenerator;
use IGamingLib\Utils\BrazilianValidator;

// Format money
$formatted = MoneyFormatter::format(1234.56);
// Returns: "R$ 1.234,56" (Brazilian format)

// Generate unique ID
$id = IDGenerator::generateUniqueId(8);
// Returns: "A3F9B2C1"

// Validate CPF (Brazilian tax ID)
$isValid = BrazilianValidator::validateCPF('123.456.789-09');
```

## 📚 Complete Documentation

### RTP Calculator

- `calculateWinFromRTP()` - Calculate win based on RTP
- `calculateAdjustedMultiplier()` - Calculate adjusted multiplier
- `calculateHouseEdge()` - Calculate house advantage
- `validateRTPRange()` - Validate RTP range
- `normalizeRTP()` - Normalize RTP to 0-100
- `calculateEffectiveRTP()` - Calculate effective RTP

### Rollover Calculator

- `calculateRequired()` - Calculate required rollover
- `calculateRemaining()` - Calculate remaining rollover
- `canWithdraw()` - Check if can withdraw
- `calculateProgress()` - Calculate progress in %
- `calculateAll()` - Calculate everything at once

### Bet Validator

- `validateAmount()` - Validate bet amount
- `validateBalance()` - Validate sufficient balance
- `calculateBalanceDistribution()` - Distribute bet across balances
- `validate()` - Complete validation

### Win Calculator

- `calculateWin()` - Calculate total win
- `calculateNetWin()` - Calculate net win
- `calculateWinWithRTP()` - Calculate win with RTP
- `calculateLoss()` - Calculate loss
- `calculateROI()` - Calculate ROI

### Weighted Random

- `generate()` - Generate random result
- `calculateProbability()` - Calculate probabilities
- `generateMultiple()` - Generate multiple results
- `generateWithSeed()` - Generate with specific seed

## 🔧 Laravel Integration

```php
// app/Http/Controllers/GameController.php
use IGamingLib\RTP\RTPCalculator;
use IGamingLib\Rollover\RolloverCalculator;

class GameController extends Controller
{
    public function play(Request $request)
    {
        $user = Auth::user();
        
        // Fetch data from Laravel
        $totalDeposited = Transaction::where('user_id', $user->id)
            ->where('type', 'deposit')
            ->sum('amount');
        
        // Use library
        $rolloverCalc = new RolloverCalculator();
        $required = $rolloverCalc->calculateRequired($totalDeposited, 3.0);
        
        return response()->json(['required' => $required]);
    }
}
```

## 🧪 Testing

```bash
composer test
```

## 📝 License

MIT License - see [LICENSE](LICENSE) for details.

## 🤝 Contributing

Contributions are welcome! Please open an issue or pull request.

## 📧 Support

For questions and support, please open an issue on GitHub.

## 💝 Donations

If you find this library useful and want to support its development, donations are greatly appreciated!

### Bitcoin (BTC)
```
bc1qmg00j0guslja5u7eqpn473gkgh2kru4r2hjv96
```

### USDT (TRON Network)
```
TTf4t652xJ33rkzxg9YFW7uHX4XyuFbmAu
```

Thank you for your support! 🙏

---

**Made with ❤️ for the iGaming community**
