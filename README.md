# Nadi for Yii 3

[![run-tests](https://github.com/nadi-pro/nadi-yii/actions/workflows/run-tests.yml/badge.svg)](https://github.com/nadi-pro/nadi-yii/actions/workflows/run-tests.yml)

Nadi monitoring SDK for Yii 3 applications. Monitor exceptions, slow queries, HTTP errors, and application performance in your Yii 3 projects.

## Requirements

- PHP 8.1+
- Yii 3

## Installation

```bash
composer require nadi-pro/nadi-yii
```

## Configuration

### 1. Add DI definitions

In your `config/di.php`, merge the Nadi definitions:

```php
use Nadi\Yii\NadiServiceProvider;

return [
    // your existing definitions...
    ...NadiServiceProvider::definitions(),
];
```

### 2. Add configuration parameters

In your `config/params.php`:

```php
return [
    // your existing params...
    'nadi' => [
        'enabled' => true,
        'driver' => 'http', // log, http, opentelemetry
        'connections' => [
            'log' => [
                'path' => '@runtime/nadi',
            ],
            'http' => [
                'api_key' => $_ENV['NADI_API_KEY'] ?? '',
                'app_key' => $_ENV['NADI_APP_KEY'] ?? '',
                'endpoint' => 'https://nadi.pro/api',
                'version' => 'v1',
            ],
            'opentelemetry' => [
                'endpoint' => 'http://localhost:4318',
                'service_name' => 'my-app',
                'service_version' => '1.0.0',
                'environment' => 'production',
            ],
        ],
        'query' => [
            'slow_threshold' => 500,
        ],
        'http' => [
            'hidden_request_headers' => ['Authorization', 'php-auth-pw'],
            'hidden_parameters' => ['password', 'password_confirmation'],
            'ignored_status_codes' => ['200-307'],
        ],
        'sampling' => [
            'strategy' => 'fixed_rate',
            'config' => [
                'sampling_rate' => 0.1,
            ],
        ],
    ],
];
```

### 3. Register middleware

In your application middleware stack, add the Nadi middleware:

```php
use Nadi\Yii\Middleware\NadiMiddleware;
use Nadi\Yii\Middleware\OpenTelemetryMiddleware;

// In your route configuration
$collector->middleware(OpenTelemetryMiddleware::class);
$collector->middleware(NadiMiddleware::class);
```

### 4. Add environment variables

```
NADI_API_KEY=your-api-key
NADI_APP_KEY=your-app-key
```

## Features

- **Exception Monitoring**: Capture and track unhandled exceptions
- **HTTP Monitoring**: Track HTTP requests and error responses via PSR-15 middleware
- **Database Monitoring**: Monitor slow SQL queries
- **OpenTelemetry**: Trace context propagation support via PSR-15 middleware
- **PSR Compliant**: Uses PSR-7 (HTTP), PSR-11 (Container), PSR-14 (Events), PSR-15 (Middleware)

## Console Commands

```bash
# Install configuration and shipper
yii nadi:install

# Test the connection
yii nadi:test

# Verify configuration
yii nadi:verify

# Update shipper binary
yii nadi:update-shipper
```

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
