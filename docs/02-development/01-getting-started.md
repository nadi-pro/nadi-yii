# Getting Started

This guide covers installation and setup of Nadi in a Yii 3 application.

## Requirements

- PHP 8.1+
- Yii 3

## Installation

```bash
composer require nadi-pro/nadi-yii
```

## Setup

### 1. Add DI Definitions

In your `config/di.php`, merge the Nadi service definitions:

```php
use Nadi\Yii\NadiServiceProvider;

return [
    // your existing definitions...
    ...NadiServiceProvider::definitions(),
];
```

This registers the `Nadi` service, event handlers, middleware, and console commands in the DI container.

### 2. Add Configuration Parameters

In your `config/params.php`, add the `nadi` key:

```php
return [
    // your existing params...
    'nadi' => [
        'enabled' => true,
        'driver' => 'http',
        'connections' => [
            'http' => [
                'api_key' => $_ENV['NADI_API_KEY'] ?? '',
                'app_key' => $_ENV['NADI_APP_KEY'] ?? '',
                'endpoint' => 'https://nadi.pro/api',
                'version' => 'v1',
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

### 3. Register Middleware

Add Nadi middleware to your application's middleware stack:

```php
use Nadi\Yii\Middleware\NadiMiddleware;
use Nadi\Yii\Middleware\OpenTelemetryMiddleware;

$collector->middleware(OpenTelemetryMiddleware::class);
$collector->middleware(NadiMiddleware::class);
```

### 4. Set Environment Variables

Add your Nadi credentials to your environment:

```text
NADI_API_KEY=your-api-key
NADI_APP_KEY=your-app-key
```

### 5. Install Configuration and Shipper

Run the install command to publish configuration and install the shipper binary:

```bash
yii nadi:install
```

### 6. Verify Setup

Test the connection to the Nadi backend:

```bash
yii nadi:test
```

Verify your configuration is valid:

```bash
yii nadi:verify
```

## Next Steps

- [Configuration Reference](../03-configuration/01-parameters.md)
- [Console Commands](03-console-commands.md)
- [Architecture Overview](../01-architecture/01-overview.md)
