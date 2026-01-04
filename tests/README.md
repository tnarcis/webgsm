# WebGSM Testing Guide

This directory contains the test suite for the WebGSM Checkout Pro plugin and associated theme modules.

## 📁 Directory Structure

```
tests/
├── php/
│   ├── unit/                          # Unit tests for PHP classes
│   │   ├── test-class-checkout-anaf.php
│   │   └── test-class-checkout-validate.php
│   └── integration/                   # Integration tests
├── js/                                # JavaScript tests
│   └── checkout.test.js
├── fixtures/                          # Mock data for tests
│   ├── anaf-responses.json
│   └── smartbill-responses.json
├── results/                           # Test results output
├── bootstrap.php                      # PHPUnit bootstrap file
└── README.md                          # This file
```

## 🚀 Quick Start

### 1. Install PHP Dependencies

```bash
composer install
```

This will install:
- PHPUnit 9.6
- Brain Monkey (for mocking WordPress functions)
- Mockery (for advanced mocking)
- PHPStan (for static analysis)

### 2. Install JavaScript Dependencies

```bash
npm install
```

This will install:
- Jest (JavaScript testing framework)
- Testing Library DOM utilities

### 3. Run Tests

#### Run All PHP Tests
```bash
composer test
# or
./vendor/bin/phpunit
```

#### Run Only Unit Tests
```bash
composer test:unit
# or
./vendor/bin/phpunit --testsuite unit
```

#### Run Only Integration Tests
```bash
composer test:integration
# or
./vendor/bin/phpunit --testsuite integration
```

#### Run JavaScript Tests
```bash
npm test
```

#### Run Tests with Coverage
```bash
# PHP Coverage
composer test:coverage
# Coverage report will be in: coverage/html/index.html

# JavaScript Coverage
npm run test:coverage
# Coverage report will be in: coverage/js/index.html
```

## 📊 Current Test Coverage

### Unit Tests (PHP)
- ✅ **ANAF Integration** (`test-class-checkout-anaf.php`)
  - County code mapping (all 42 Romanian counties)
  - Diacritics removal
  - ANAF response parsing
  - TVA vs non-TVA companies

- ✅ **Checkout Validation** (`test-class-checkout-validate.php`)
  - Phone normalization
  - CUI validation
  - CNP validation
  - Email validation
  - Customer type detection

### JavaScript Tests
- ✅ **Checkout Form** (`checkout.test.js`)
  - Phone normalization
  - CUI validation
  - CNP validation
  - Email validation

## 🎯 Next Steps - Expand Test Coverage

### Priority 1: Complete Unit Tests

1. **SmartBill Integration Tests**
   ```bash
   tests/php/unit/test-smartbill-facturi.php
   ```
   - Mock SmartBill API calls
   - Test invoice generation for PF/PJ
   - Test duplicate prevention
   - Test API disabled mode

2. **Checkout Save Tests**
   ```bash
   tests/php/unit/test-class-checkout-save.php
   ```
   - Test order meta saving (CPT + HPOS)
   - Test user profile sync
   - Test card list updates
   - Test duplicate card prevention

### Priority 2: Integration Tests

1. **Full Checkout Flow - PF**
   ```bash
   tests/php/integration/test-checkout-flow-pf.php
   ```
   - Guest checkout
   - Logged-in user checkout
   - Card selection and save
   - Order creation

2. **Full Checkout Flow - PJ**
   ```bash
   tests/php/integration/test-checkout-flow-pj.php
   ```
   - ANAF autocomplete
   - Company data save
   - Invoice generation
   - Order metadata

### Priority 3: End-to-End Tests

Consider adding:
- Browser automation tests (Playwright/Puppeteer)
- WooCommerce checkout flow tests
- Payment gateway integration tests

## 🔧 Advanced Configuration

### WordPress Test Suite (Optional)

For integration tests that need full WordPress environment:

```bash
# Install WordPress test suite
cd tests
./bin/install-wp-tests.sh wordpress_test root '' localhost latest
```

This will:
1. Download WordPress test library
2. Create test database
3. Configure WordPress for testing

### Environment Variables

Configure in `phpunit.xml`:

```xml
<env name="WP_TESTS_DIR" value="/tmp/wordpress-tests-lib"/>
<env name="WP_CORE_DIR" value="/tmp/wordpress"/>
```

## 📝 Writing New Tests

### Example Unit Test

```php
<?php
namespace WebGSM\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Brain\Monkey;

class Test_My_Feature extends TestCase {

    protected function setUp(): void {
        parent::setUp();
        Monkey\setUp();
    }

    protected function tearDown(): void {
        Monkey\tearDown();
        parent::tearDown();
    }

    public function test_my_feature() {
        $this->assertTrue(true);
    }
}
```

### Example JavaScript Test

```javascript
describe('My Feature', () => {
  test('should do something', () => {
    expect(myFunction()).toBe(expected);
  });
});
```

## 🐛 Debugging Tests

### Verbose Output
```bash
./vendor/bin/phpunit --verbose
npm run test:verbose
```

### Run Single Test File
```bash
./vendor/bin/phpunit tests/php/unit/test-class-checkout-anaf.php
npm test -- checkout.test.js
```

### Run Single Test Method
```bash
./vendor/bin/phpunit --filter test_remove_diacritics
```

## 📈 CI/CD Integration

### GitHub Actions Example

```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.0'
      - name: Install dependencies
        run: composer install
      - name: Run tests
        run: composer test
      - name: Upload coverage
        uses: codecov/codecov-action@v2
```

## 🎓 Resources

- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Brain Monkey Documentation](https://giuseppe-mazzapica.gitbook.io/brain-monkey/)
- [Jest Documentation](https://jestjs.io/docs/getting-started)
- [Testing Library](https://testing-library.com/docs/)

## ⚠️ Important Notes

1. **Don't commit** `vendor/` or `node_modules/` - they're in `.gitignore`
2. **Do commit** test files and configuration
3. **Run tests** before pushing code
4. **Aim for 80%+** code coverage on critical paths
5. **Mock external APIs** (ANAF, SmartBill) in unit tests

## 🤝 Contributing

When adding new features:
1. Write tests FIRST (TDD)
2. Ensure all tests pass
3. Aim for >80% coverage
4. Update this README if needed

---

**Last Updated:** January 2025
**Test Framework Versions:** PHPUnit 9.6, Jest 29.7
