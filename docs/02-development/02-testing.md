# Testing

This page covers the test suite, code formatting, and CI/CD workflows for the Nadi Yii 3 SDK.

## Running Tests

```bash
composer test
```

This runs PHPUnit with the configuration in `phpunit.xml`.

## Test Suite Structure

```text
tests/
├── TestCase.php                # Base test case with getNadiConfig() helper
└── Feature/
    ├── ServiceProviderTest.php # DI container registration
    ├── HandlerTest.php         # Event handler instantiation
    ├── MiddlewareTest.php      # Middleware instantiation
    └── TransporterTest.php     # Transporter configuration
```

The base `TestCase` class provides a `getNadiConfig()` method that returns a standard configuration array for use in tests.

## Code Coverage

PHPUnit is configured to collect coverage for:

- `src/` - All source files
- `config/` - Configuration files

## Code Formatting

The project uses [Laravel Pint](https://laravel.com/docs/pint) for code style:

```bash
composer format
```

To check formatting without making changes:

```bash
./vendor/bin/pint --test
```

## CI/CD

### Test Workflow

The `.github/workflows/run-tests.yml` workflow runs on push and PR to `main` and `2.x` branches:

- Tests against PHP 8.1, 8.2, 8.3, and 8.4
- Runs the full PHPUnit test suite
- Checks code formatting with Pint

### Changelog Workflow

The `.github/workflows/update-changelog.yml` workflow automatically updates `CHANGELOG.md` when a GitHub release is created.

## Next Steps

- [Console Commands](03-console-commands.md)
- [Getting Started](01-getting-started.md)
