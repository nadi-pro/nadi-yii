# Handlers

Event handlers capture monitoring data from the application. All handlers extend `Handler\Base`,
which provides `store()` and `hash()` methods.

## HandleExceptionEvent

Captures unhandled exceptions.

**Namespace:** `Nadi\Yii\Handler\HandleExceptionEvent`

**Captured data:**

- Exception class, message, code
- File and line number
- Formatted stack trace frames
- Surrounding source code (20-line window via `ExceptionContext`)
- SHA1 hash family for grouping

**Usage:** Invoked by `NadiMiddleware` when an exception is caught, or directly via `Nadi::recordException()`.

## HandleHttpRequestEvent

Captures HTTP request/response data.

**Namespace:** `Nadi\Yii\Handler\HandleHttpRequestEvent`

**Captured data:**

- HTTP method, URI, status code
- Request and response headers (sensitive headers redacted)
- Request payload (sensitive parameters redacted)
- OpenTelemetry semantic conventions

**Filtering:**

- Headers in `hidden_request_headers` are replaced with `***`
- Parameters in `hidden_parameters` are replaced with `***`
- Responses with status codes in `ignored_status_codes` are skipped

**Usage:** Called by `NadiMiddleware` after the request handler returns a response.

## HandleQueryEvent

Captures slow database queries.

**Namespace:** `Nadi\Yii\Handler\HandleQueryEvent`

**Captured data:**

- SQL query text
- Duration in milliseconds
- Connection name
- Query type (SELECT, INSERT, UPDATE, DELETE) and table name
- Calling code location (vendor frames filtered out)

**Threshold:** Only queries exceeding the `slow_threshold` config value are recorded.

**Usage:** Attach to database query events in your application. Requires `yiisoft/db`.

## Base Handler

**Namespace:** `Nadi\Yii\Handler\Base`

Abstract class providing:

- `store(Entry|ExceptionEntry $entry)` - Sends entry to the transporter
- `hash(string $value): string` - SHA1 hash helper

## Next Steps

- [Middleware](02-middleware.md)
- [Data Flow](../01-architecture/02-data-flow.md)
