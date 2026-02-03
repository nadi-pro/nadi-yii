# Transport Drivers

Nadi supports three transport drivers for shipping monitoring data.

## Log Driver

Writes entries to a local file. Useful for development and debugging.

```php
'driver' => 'log',
'connections' => [
    'log' => [
        'path' => '@runtime/nadi',
    ],
],
```

| Key    | Type     | Description                                                         |
|--------|----------|---------------------------------------------------------------------|
| `path` | `string` | Directory path for log files. Supports Yii aliases like `@runtime`. |

## HTTP Driver

Sends entries to the Nadi API over HTTP. This is the standard production driver.

```php
'driver' => 'http',
'connections' => [
    'http' => [
        'api_key' => $_ENV['NADI_API_KEY'] ?? '',
        'app_key' => $_ENV['NADI_APP_KEY'] ?? '',
        'endpoint' => 'https://nadi.pro/api',
        'version' => 'v1',
    ],
],
```

| Key        | Type     | Description       |
|------------|----------|-------------------|
| `api_key`  | `string` | Your Nadi API key |
| `app_key`  | `string` | Your Nadi app key |
| `endpoint` | `string` | API endpoint URL  |
| `version`  | `string` | API version       |

## OpenTelemetry Driver

Sends entries to an OTLP-compatible endpoint for distributed tracing.

```php
'driver' => 'opentelemetry',
'connections' => [
    'opentelemetry' => [
        'endpoint' => 'http://localhost:4318',
        'service_name' => 'my-app',
        'service_version' => '1.0.0',
        'environment' => 'production',
    ],
],
```

| Key               | Type     | Description                |
|-------------------|----------|----------------------------|
| `endpoint`        | `string` | OTLP collector endpoint    |
| `service_name`    | `string` | Service name for traces    |
| `service_version` | `string` | Service version for traces |
| `environment`     | `string` | Deployment environment     |

## Next Steps

- [Sampling Strategies](03-sampling-strategies.md)
- [Parameters Reference](01-parameters.md)
