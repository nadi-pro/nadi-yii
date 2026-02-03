# Overview

Nadi for Yii 3 is a monitoring SDK that captures runtime data from Yii 3 applications and ships it
to a backend for analysis. It follows PSR standards and integrates with the Yii 3 dependency
injection and event systems.

## Component Map

```text
src/
├── Nadi.php                    # Main service - entry point for all monitoring
├── NadiServiceProvider.php     # DI definitions for Yii 3 container
├── Transporter.php             # Configures transport driver and sampling
├── Actions/
│   ├── ExceptionContext.php    # Extracts surrounding code from exception location
│   └── ExtractProperties.php   # Reflection-based property extraction
├── Command/
│   ├── InstallCommand.php      # yii nadi:install
│   ├── TestCommand.php         # yii nadi:test
│   ├── VerifyCommand.php       # yii nadi:verify
│   └── UpdateShipperCommand.php # yii nadi:update-shipper
├── Concerns/
│   ├── FetchesStackTrace.php   # Stack trace extraction trait
│   └── InteractsWithMetric.php # Metric registration trait
├── Data/
│   ├── Entry.php               # HTTP/query entry data class
│   └── ExceptionEntry.php      # Exception entry data class
├── Handler/
│   ├── Base.php                # Abstract handler with store/hash methods
│   ├── HandleExceptionEvent.php
│   ├── HandleHttpRequestEvent.php
│   └── HandleQueryEvent.php
├── Metric/
│   ├── Application.php         # App environment metric
│   ├── Framework.php           # Yii framework version metric
│   ├── Http.php                # HTTP attributes metric
│   └── Network.php             # Network attributes metric
├── Middleware/
│   ├── NadiMiddleware.php      # Main monitoring middleware (PSR-15)
│   └── OpenTelemetryMiddleware.php # Trace context middleware (PSR-15)
├── Shipper/
│   └── Shipper.php             # Binary shipper manager wrapper
└── Support/
    └── OpenTelemetrySemanticConventions.php
```

## PSR Standards

The SDK implements the following PSR standards:

| Standard | Interface                                        | Usage                    |
|----------|--------------------------------------------------|--------------------------|
| PSR-7    | `ServerRequestInterface`, `ResponseInterface`    | HTTP message handling    |
| PSR-11   | `ContainerInterface`                             | Dependency injection     |
| PSR-14   | `EventDispatcherInterface`                       | Event-driven monitoring  |
| PSR-15   | `MiddlewareInterface`, `RequestHandlerInterface` | HTTP middleware pipeline |

## Dependencies

The SDK depends on two key packages:

- **`nadi-pro/nadi-php`** - Core Nadi library providing transport drivers, sampling, and data structures
- **`yiisoft/config`** - Yii 3 configuration plugin for automatic merge-plan generation

All Yii 3 framework packages (`yii-console`, `yii-event`, `yii-http`) are used for framework integration.

## Next Steps

- [Data Flow](02-data-flow.md)
- [Getting Started](../02-development/01-getting-started.md)
