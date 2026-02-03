# Sampling Strategies

Sampling controls what percentage of events are captured and sent to the backend.
This helps manage data volume and cost in high-traffic applications.

## Configuration

```php
'sampling' => [
    'strategy' => 'fixed_rate',
    'config' => [
        'sampling_rate' => 0.1,
    ],
],
```

## Available Strategies

### Fixed Rate

Samples a fixed percentage of all events.

```php
'strategy' => 'fixed_rate',
'config' => [
    'sampling_rate' => 0.1, // 10% of events
],
```

| Key             | Type    | Default | Description                                   |
|-----------------|---------|---------|-----------------------------------------------|
| `sampling_rate` | `float` | `0.1`   | Rate between 0.0 (none) and 1.0 (all)        |

### Dynamic Rate

Adjusts the sampling rate based on system load.

```php
'strategy' => 'dynamic_rate',
'config' => [
    'base_rate' => 0.1,
    'load_factor' => 0.5,
],
```

| Key           | Type    | Description                         |
|---------------|---------|-------------------------------------|
| `base_rate`   | `float` | Base sampling rate                  |
| `load_factor` | `float` | Factor to adjust rate based on load |

### Interval

Samples events at regular time intervals.

```php
'strategy' => 'interval',
'config' => [
    'interval_seconds' => 60,
],
```

| Key                | Type  | Description                    |
|--------------------|-------|--------------------------------|
| `interval_seconds` | `int` | Seconds between sampled events |

### Peak Load

Adjusts sampling during high load periods to reduce overhead.

```php
'strategy' => 'peak_load',
'config' => [
    'base_rate' => 0.1,
    'load_factor' => 0.5,
],
```

| Key           | Type    | Description                          |
|---------------|---------|--------------------------------------|
| `base_rate`   | `float` | Base sampling rate                   |
| `load_factor` | `float` | Factor to reduce sampling under load |

## Next Steps

- [Parameters Reference](01-parameters.md)
- [Transport Drivers](02-transport-drivers.md)
