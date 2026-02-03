# Parameters

This page is the full reference for the `nadi` configuration array in `config/params.php`.

## Configuration Structure

```php
'nadi' => [
    'enabled' => true,
    'driver' => 'http',
    'connections' => [ ... ],
    'query' => [ ... ],
    'http' => [ ... ],
    'sampling' => [ ... ],
],
```

## Top-Level Options

| Key       | Type     | Default | Description                                         |
|-----------|----------|---------|-----------------------------------------------------|
| `enabled` | `bool`   | `true`  | Enable or disable monitoring                        |
| `driver`  | `string` | `'log'` | Transport driver: `log`, `http`, or `opentelemetry` |

## Query Monitoring

```php
'query' => [
    'slow_threshold' => 500,
],
```

| Key              | Type  | Default | Description                                      |
|------------------|-------|---------|--------------------------------------------------|
| `slow_threshold` | `int` | `500`   | Minimum query duration in milliseconds to record |

## HTTP Filtering

```php
'http' => [
    'hidden_request_headers' => ['Authorization', 'php-auth-pw'],
    'hidden_parameters' => ['password', 'password_confirmation'],
    'ignored_status_codes' => ['200-307'],
],
```

| Key                      | Type    | Default                                  | Description                              |
|--------------------------|---------|------------------------------------------|------------------------------------------|
| `hidden_request_headers` | `array` | `['Authorization', 'php-auth-pw']`       | Request headers to redact                |
| `hidden_parameters`      | `array` | `['password', 'password_confirmation']`  | Request parameters to redact             |
| `ignored_status_codes`   | `array` | `['200-307']`                            | Status codes or ranges to skip recording |

Status codes support range notation (e.g., `'200-307'`) and individual codes (e.g., `'404'`).

## Environment Variables

| Variable               | Description                                        |
|------------------------|----------------------------------------------------|
| `NADI_API_KEY`         | API key for the HTTP driver                        |
| `NADI_APP_KEY`         | App key for the HTTP driver                        |
| `APP_NAME`             | Service name (default: `'yii-app'`)                |
| `APP_VERSION`          | Service version (default: `'1.0.0'`)               |
| `APP_ENV` / `YII_ENV`  | Application environment (default: `'production'`)  |

## Next Steps

- [Transport Drivers](02-transport-drivers.md)
- [Sampling Strategies](03-sampling-strategies.md)
