# Middleware

Nadi provides two PSR-15 middleware classes for HTTP monitoring and OpenTelemetry trace context.

## NadiMiddleware

**Namespace:** `Nadi\Yii\Middleware\NadiMiddleware`

**Implements:** `Psr\Http\Server\MiddlewareInterface`

The main monitoring middleware. Wraps the request handler to capture HTTP data and exceptions.

**Behavior:**

1. Passes the request to the next handler
2. On success: calls `HandleHttpRequestEvent` with the request and response
3. On exception: records the exception via `Nadi::recordException()`, calls `HandleHttpRequestEvent`, then re-throws

**Registration:**

```php
use Nadi\Yii\Middleware\NadiMiddleware;

$collector->middleware(NadiMiddleware::class);
```

## OpenTelemetryMiddleware

**Namespace:** `Nadi\Yii\Middleware\OpenTelemetryMiddleware`

**Implements:** `Psr\Http\Server\MiddlewareInterface`

Extracts and propagates W3C Trace Context headers for distributed tracing.

**Behavior:**

1. Reads the `traceparent` header from the incoming request
2. Parses the trace context: version, trace ID, span ID, trace flags
3. Passes the request to the next handler
4. Injects trace context into the response headers
5. Gracefully handles cases where OpenTelemetry is not installed

**Registration:**

```php
use Nadi\Yii\Middleware\OpenTelemetryMiddleware;

// Register before NadiMiddleware
$collector->middleware(OpenTelemetryMiddleware::class);
$collector->middleware(NadiMiddleware::class);
```

> **Note**: Register `OpenTelemetryMiddleware` before `NadiMiddleware` so trace context is available when HTTP data is captured.

## Next Steps

- [Metrics](03-metrics.md)
- [Handlers](01-handlers.md)
