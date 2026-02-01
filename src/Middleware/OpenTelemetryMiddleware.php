<?php

namespace Nadi\Yii\Middleware;

use Nadi\Support\OpenTelemetrySemanticConventions;
use Nadi\Yii\Nadi;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class OpenTelemetryMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Nadi $nadi,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (! $this->nadi->isEnabled()) {
            return $handler->handle($request);
        }

        if (! class_exists(\OpenTelemetry\API\Trace\TracerProvider::class)) {
            return $handler->handle($request);
        }

        $traceparent = $request->getHeaderLine('traceparent');
        $tracestate = $request->getHeaderLine('tracestate');

        $spanContext = null;
        if ($traceparent) {
            $spanContext = $this->extractTraceContext($traceparent);
        }

        $response = $handler->handle($request);

        // Inject trace context into response headers if available
        if ($spanContext && isset($spanContext['trace_id'], $spanContext['span_id'])) {
            $response = $response->withHeader('traceparent', sprintf(
                '00-%s-%s-01',
                $spanContext['trace_id'],
                $spanContext['span_id'],
            ));
        }

        return $response;
    }

    protected function extractTraceContext(string $traceparent): ?array
    {
        $parts = explode('-', $traceparent);

        if (count($parts) < 4) {
            return null;
        }

        return [
            'version' => $parts[0],
            'trace_id' => $parts[1],
            'span_id' => $parts[2],
            'trace_flags' => $parts[3],
        ];
    }
}
