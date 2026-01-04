# Testing & CI/CD

[![Tests](https://github.com/tnarcis/webgsm/actions/workflows/tests.yml/badge.svg)](https://github.com/tnarcis/webgsm/actions/workflows/tests.yml)
[![codecov](https://codecov.io/gh/tnarcis/webgsm/branch/main/graph/badge.svg)](https://codecov.io/gh/tnarcis/webgsm)

## Quick Start

### Run All Tests
```bash
# PHP tests
composer test

# JavaScript tests
npm test
```

### Run with Coverage
```bash
# PHP coverage (generates HTML report)
composer test:coverage
# Open: coverage/html/index.html

# JavaScript coverage
npm run test:coverage
# Open: coverage/js/index.html
```

## Continuous Integration

Every push and pull request automatically triggers:

### ✅ PHP Tests (Multiple Versions)
- PHP 7.4, 8.0, 8.1
- Unit tests for all core classes
- Integration tests for checkout flows
- Code coverage reporting

### ✅ JavaScript Tests
- Jest tests for validation functions
- DOM manipulation tests
- Code coverage reporting

### ✅ Code Quality Checks
- PHPStan static analysis (level 5)
- PHP syntax validation
- Code style checks

## Test Matrix

| Component | Coverage | Status | Priority |
|-----------|----------|--------|----------|
| ANAF Integration | 45% | 🟡 In Progress | 🔴 High |
| Checkout Validation | 60% | 🟡 In Progress | 🔴 High |
| Checkout Save | 0% | ⚪ Todo | 🔴 High |
| SmartBill API | 0% | ⚪ Todo | 🔴 High |
| JavaScript | 35% | 🟡 In Progress | 🟡 Medium |

## GitHub Actions Workflows

### Tests Workflow (`.github/workflows/tests.yml`)

Runs on:
- Push to `main` branch
- Push to any `claude/**` branch
- All pull requests to `main`

**Jobs:**
1. **php-tests** - Runs PHPUnit tests on PHP 7.4, 8.0, 8.1
2. **javascript-tests** - Runs Jest tests with Node.js 18
3. **code-quality** - Runs PHPStan and syntax checks
4. **test-summary** - Aggregates all results

### Coverage Reporting

Coverage reports are automatically uploaded to Codecov:
- PHP coverage: `coverage/clover.xml`
- JavaScript coverage: `coverage/js/lcov.info`

View detailed coverage at: https://codecov.io/gh/tnarcis/webgsm

## Local Development

### Prerequisites
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### Running Tests Locally

#### PHP Unit Tests
```bash
# All tests
composer test

# Only unit tests
composer test:unit

# Only integration tests
composer test:integration

# With coverage
composer test:coverage
```

#### JavaScript Tests
```bash
# Run once
npm test

# Watch mode (auto-rerun on changes)
npm run test:watch

# With coverage
npm run test:coverage

# Verbose output
npm run test:verbose
```

#### Static Analysis
```bash
# Run PHPStan
composer analyse

# Fix automatically fixable issues
composer fix
```

### Writing New Tests

See detailed guide in [tests/README.md](tests/README.md)

## CI/CD Status

Check the status of recent test runs:
- [GitHub Actions](https://github.com/tnarcis/webgsm/actions)
- [Codecov Dashboard](https://codecov.io/gh/tnarcis/webgsm)

## Troubleshooting

### Tests Failing Locally?

1. **Clear caches:**
   ```bash
   rm -rf vendor/
   composer install
   composer dump-autoload
   ```

2. **Check PHP version:**
   ```bash
   php -v  # Should be 7.4+
   ```

3. **Verify dependencies:**
   ```bash
   composer validate
   npm ls
   ```

### CI Tests Failing?

1. Check the [Actions tab](https://github.com/tnarcis/webgsm/actions) for detailed logs
2. Look for red ❌ marks in the workflow
3. Click on failed job to see detailed error messages
4. Common issues:
   - Syntax errors in PHP/JavaScript
   - Missing dependencies
   - Test assertions that depend on environment

## Coverage Goals

| Period | Target Coverage | Current |
|--------|----------------|---------|
| Q1 2025 | 60% | 35% |
| Q2 2025 | 80% | - |
| Q3 2025 | 90% | - |

Critical paths (checkout, ANAF, SmartBill) should maintain **90%+ coverage** at all times.

---

**Last Updated:** January 2025
**CI/CD Platform:** GitHub Actions
**Coverage Platform:** Codecov
