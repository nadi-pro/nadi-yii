# Data Flow

This page describes how monitoring data flows from capture to transport in a Yii 3 application using Nadi.

## Request Lifecycle

```text
HTTP Request
    │
    ▼
OpenTelemetryMiddleware
    │  Extracts W3C traceparent header
    │  Parses trace_id, span_id, trace_flags
    │
    ▼
NadiMiddleware
    │  Records start time
    │  Wraps request handler in try/catch
    │
    ▼
Application Logic
    │  ├── Database queries → HandleQueryEvent (if above threshold)
    │  └── Exceptions → HandleExceptionEvent
    │
    ▼
NadiMiddleware (response)
    │  Calls HandleHttpRequestEvent with request/response
    │  Catches and re-throws exceptions
    │
    ▼
Nadi::__destruct()
    │  Calls send() on transporter
    │
    ▼
Transporter → Backend (log file / HTTP API / OpenTelemetry endpoint)
```

## Event Handlers

### Exception Handler

`HandleExceptionEvent` captures:

- Exception class, message, code, file, line
- Full stack trace with formatted frames
- Surrounding source code (20-line window)
- SHA1 hash for grouping related exceptions

### HTTP Request Handler

`HandleHttpRequestEvent` captures:

- HTTP method, URI, status code
- Request/response headers (with sensitive headers filtered)
- Request payload (with sensitive parameters filtered)
- OpenTelemetry semantic conventions
- Only records responses outside the ignored status code range

### Query Handler

`HandleQueryEvent` captures:

- SQL query text
- Query duration in milliseconds
- Connection name
- Query type (SELECT, INSERT, etc.) and table name
- Calling code location (filters out vendor frames)

Only queries exceeding the `slow_threshold` are recorded.

## Data Classes

Captured data is wrapped in two entry types:

- **`Entry`** - HTTP requests and database queries. Extends the core `Nadi\Data\Entry` and registers metrics on creation.
- **`ExceptionEntry`** - Exceptions. Extends `Nadi\Data\ExceptionEntry` and registers metrics on creation.

Both types use the `InteractsWithMetric` trait to automatically collect framework, application, HTTP, and network metrics.

## Transport

The `Transporter` class configures the delivery mechanism based on the selected driver:

| Driver           | Destination                   | Use Case              |
|------------------|-------------------------------|-----------------------|
| `log`            | Local file at `@runtime/nadi` | Development/debugging |
| `http`           | Nadi API endpoint             | Production monitoring |
| `opentelemetry`  | OTLP endpoint                 | Distributed tracing   |

Sampling is applied before transport to control data volume. See [Sampling Strategies](../03-configuration/03-sampling-strategies.md).

## Next Steps

- [Configuration Parameters](../03-configuration/01-parameters.md)
- [Handlers Reference](../04-api/01-handlers.md)
