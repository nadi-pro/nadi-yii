# Metrics

Metric collectors attach contextual attributes to every monitoring entry. They are registered
automatically via the `InteractsWithMetric` trait on `Entry` and `ExceptionEntry`.

## Framework

**Namespace:** `Nadi\Yii\Metric\Framework`

Provides framework identification:

| Attribute           | Value                                                    |
|---------------------|----------------------------------------------------------|
| `framework.name`    | `yii`                                                    |
| `framework.version` | Resolved from `composer.lock` (yiisoft/yii-http version) |
| `service.name`      | From `APP_NAME` env var (default: `'yii-app'`)           |
| `service.version`   | From `APP_VERSION` env var (default: `'1.0.0'`)          |

## Application

**Namespace:** `Nadi\Yii\Metric\Application`

Provides application environment:

| Attribute         | Value                                                         |
|-------------------|---------------------------------------------------------------|
| `app.environment` | From `APP_ENV` or `YII_ENV` env var (default: `'production'`) |

## Http

**Namespace:** `Nadi\Yii\Metric\Http`

Provides HTTP semantic conventions from PHP superglobals via `OpenTelemetrySemanticConventions`.

## Network

**Namespace:** `Nadi\Yii\Metric\Network`

Provides network attributes:

| Attribute            | Value              |
|----------------------|--------------------|
| `net.host.name`      | Server hostname    |
| `net.host.port`      | Server port        |
| `net.protocol.name`  | `http` or `https`  |

## InteractsWithMetric Trait

**Namespace:** `Nadi\Yii\Concerns\InteractsWithMetric`

Used by `Entry` and `ExceptionEntry` to register all four metric collectors on instantiation.
Calls `registerMetrics()` which adds Http, Framework, Application, and Network metrics
to the entry.

## Next Steps

- [Handlers](01-handlers.md)
- [Architecture Overview](../01-architecture/01-overview.md)
