<?php

namespace Nadi\Yii;

use Psr\Container\ContainerInterface;

/**
 * Yii 3 service provider for Nadi monitoring.
 *
 * Add to your config/di.php:
 * return [
 *     ...Nadi\Yii\NadiServiceProvider::definitions(),
 * ];
 */
class NadiServiceProvider
{
    /**
     * Returns DI container definitions for Nadi services.
     */
    public static function definitions(): array
    {
        return [
            Nadi::class => static function (ContainerInterface $container) {
                $params = $container->get('nadi.config');

                return new Nadi($params);
            },

            Handler\HandleExceptionEvent::class => static function (ContainerInterface $container) {
                return new Handler\HandleExceptionEvent(
                    $container->get(Nadi::class),
                );
            },

            Handler\HandleHttpRequestEvent::class => static function (ContainerInterface $container) {
                $params = $container->get('nadi.config');

                return new Handler\HandleHttpRequestEvent(
                    $container->get(Nadi::class),
                    $params,
                );
            },

            Handler\HandleQueryEvent::class => static function (ContainerInterface $container) {
                return new Handler\HandleQueryEvent(
                    $container->get(Nadi::class),
                );
            },

            Middleware\NadiMiddleware::class => static function (ContainerInterface $container) {
                $params = $container->get('nadi.config');

                return new Middleware\NadiMiddleware(
                    $container->get(Nadi::class),
                    $params,
                );
            },

            Middleware\OpenTelemetryMiddleware::class => static function (ContainerInterface $container) {
                return new Middleware\OpenTelemetryMiddleware(
                    $container->get(Nadi::class),
                );
            },
        ];
    }
}
