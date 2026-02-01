<?php

namespace Nadi\Yii\Handler;

use Nadi\Data\Type;
use Nadi\Yii\Data\Entry;
use Nadi\Yii\Nadi;
use Nadi\Yii\Support\OpenTelemetrySemanticConventions;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HandleHttpRequestEvent extends Base
{
    private array $config;

    public function __construct(
        Nadi $nadi,
        array $config,
    ) {
        parent::__construct($nadi);
        $this->config = $config;
    }

    public function handle(ServerRequestInterface $request, ResponseInterface $response): void
    {
        if (! $this->nadi->isEnabled()) {
            return;
        }

        $statusCode = $response->getStatusCode();
        $httpConfig = $this->config['http'] ?? [];

        if ($this->isIgnoredStatusCode($statusCode, $httpConfig['ignored_status_codes'] ?? [])) {
            return;
        }

        $entry = new Entry(Type::HTTP);

        $entry->content = [
            'method' => $request->getMethod(),
            'uri' => (string) $request->getUri(),
            'status_code' => $statusCode,
            'headers' => $this->filterHeaders(
                $this->getHeaders($request),
                $httpConfig['hidden_request_headers'] ?? [],
            ),
            'payload' => $this->filterParameters(
                $request->getParsedBody() ?? [],
                $httpConfig['hidden_parameters'] ?? [],
            ),
            'response_status' => $statusCode,
        ];

        $entry->content = array_merge(
            $entry->content,
            OpenTelemetrySemanticConventions::httpAttributesFromPsr7($request, $response),
        );

        $this->store($entry->toArray());
    }

    protected function getHeaders(ServerRequestInterface $request): array
    {
        $headers = [];
        foreach ($request->getHeaders() as $name => $values) {
            $headers[$name] = implode(', ', $values);
        }

        return $headers;
    }

    protected function filterHeaders(array $headers, array $hidden): array
    {
        $hiddenLower = array_map('strtolower', $hidden);

        foreach ($headers as $key => $value) {
            if (in_array(strtolower($key), $hiddenLower, true)) {
                $headers[$key] = '********';
            }
        }

        return $headers;
    }

    protected function filterParameters(array $parameters, array $hidden): array
    {
        foreach ($hidden as $key) {
            if (isset($parameters[$key])) {
                $parameters[$key] = '********';
            }
        }

        return $parameters;
    }

    protected function isIgnoredStatusCode(int $statusCode, array $ignoredRanges): bool
    {
        foreach ($ignoredRanges as $range) {
            if (str_contains($range, '-')) {
                [$min, $max] = explode('-', $range);
                if ($statusCode >= (int) $min && $statusCode <= (int) $max) {
                    return true;
                }
            } elseif ((int) $range === $statusCode) {
                return true;
            }
        }

        return false;
    }
}
