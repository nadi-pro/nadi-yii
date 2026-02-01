<?php

namespace Nadi\Yii\Support;

use Nadi\Support\OpenTelemetrySemanticConventions as CoreConventions;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class OpenTelemetrySemanticConventions extends CoreConventions
{
    public const YII_CONTROLLER = 'yii.controller';

    public const YII_ACTION = 'yii.action';

    public const YII_ROUTE = 'yii.route';

    public const DB_CONNECTION_NAME = 'db.connection.name';

    public static function httpAttributesFromPsr7(ServerRequestInterface $request, ?ResponseInterface $response = null): array
    {
        $uri = $request->getUri();

        $attributes = [
            self::HTTP_METHOD => $request->getMethod(),
            self::HTTP_URL => (string) $uri,
            self::HTTP_SCHEME => $uri->getScheme(),
            self::HTTP_HOST => $uri->getHost(),
            self::HTTP_TARGET => $uri->getPath() . ($uri->getQuery() ? '?' . $uri->getQuery() : ''),
        ];

        $userAgent = $request->getHeaderLine('User-Agent');
        if ($userAgent) {
            $attributes[self::HTTP_USER_AGENT] = $userAgent;
        }

        $serverParams = $request->getServerParams();
        if (isset($serverParams['REMOTE_ADDR'])) {
            $attributes[self::HTTP_CLIENT_IP] = $serverParams['REMOTE_ADDR'];
        }

        if ($response) {
            $attributes[self::HTTP_STATUS_CODE] = $response->getStatusCode();
        }

        return $attributes;
    }

    public static function httpAttributesFromGlobals(): array
    {
        $attributes = [];

        if (isset($_SERVER['REQUEST_METHOD'])) {
            $attributes[self::HTTP_METHOD] = $_SERVER['REQUEST_METHOD'];
        }

        if (isset($_SERVER['REQUEST_URI'])) {
            $scheme = (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';
            $attributes[self::HTTP_URL] = $scheme . '://' . $host . $_SERVER['REQUEST_URI'];
            $attributes[self::HTTP_SCHEME] = $scheme;
            $attributes[self::HTTP_HOST] = $host;
            $attributes[self::HTTP_TARGET] = $_SERVER['REQUEST_URI'];
        }

        return $attributes;
    }

    public static function databaseAttributes(string $connectionName, string $query, float $duration): array
    {
        $attributes = [
            self::DB_SYSTEM => 'unknown',
            self::DB_STATEMENT => $query,
            self::DB_QUERY_DURATION => $duration,
            self::DB_CONNECTION_NAME => $connectionName,
        ];

        if (preg_match('/^\s*(SELECT|INSERT|UPDATE|DELETE|CREATE|DROP|ALTER|TRUNCATE)\s+/i', $query, $matches)) {
            $attributes[self::DB_OPERATION] = strtoupper($matches[1]);
        }

        if (preg_match('/(?:FROM|INTO|UPDATE|TABLE)\s+`?(\w+)`?/i', $query, $matches)) {
            $attributes[self::DB_SQL_TABLE] = $matches[1];
        }

        return $attributes;
    }

    public static function userAttributes(): array
    {
        return [];
    }

    public static function sessionAttributes(): array
    {
        if (session_status() === PHP_SESSION_ACTIVE && session_id()) {
            return [self::SESSION_ID => session_id()];
        }

        return [];
    }

    public static function exceptionAttributes(\Throwable $exception): array
    {
        return parent::exceptionAttributes($exception);
    }

    public static function performanceAttributes(float $startTime, ?int $memoryPeak = null): array
    {
        return parent::performanceAttributes($startTime, $memoryPeak);
    }
}
