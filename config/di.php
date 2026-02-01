<?php

use Nadi\Yii\Command\InstallCommand;
use Nadi\Yii\Command\TestCommand;
use Nadi\Yii\Command\UpdateShipperCommand;
use Nadi\Yii\Command\VerifyCommand;
use Nadi\Yii\Handler\HandleExceptionEvent;
use Nadi\Yii\Handler\HandleHttpRequestEvent;
use Nadi\Yii\Handler\HandleQueryEvent;
use Nadi\Yii\Middleware\NadiMiddleware;
use Nadi\Yii\Middleware\OpenTelemetryMiddleware;
use Nadi\Yii\Nadi;
use Psr\Container\ContainerInterface;

return [
    Nadi::class => static function (ContainerInterface $container) {
        $params = $container->get('nadi.config');

        return new Nadi($params);
    },

    'nadi.config' => static function (ContainerInterface $container) {
        $params = $container->has('params') ? $container->get('params') : [];

        return $params['nadi'] ?? [];
    },

    HandleExceptionEvent::class => static function (ContainerInterface $container) {
        return new HandleExceptionEvent($container->get(Nadi::class));
    },

    HandleHttpRequestEvent::class => static function (ContainerInterface $container) {
        $params = $container->get('nadi.config');

        return new HandleHttpRequestEvent($container->get(Nadi::class), $params);
    },

    HandleQueryEvent::class => static function (ContainerInterface $container) {
        return new HandleQueryEvent($container->get(Nadi::class));
    },

    NadiMiddleware::class => static function (ContainerInterface $container) {
        $params = $container->get('nadi.config');

        return new NadiMiddleware($container->get(Nadi::class), $params);
    },

    OpenTelemetryMiddleware::class => static function (ContainerInterface $container) {
        return new OpenTelemetryMiddleware($container->get(Nadi::class));
    },

    InstallCommand::class => static function (ContainerInterface $container) {
        $aliases = $container->has('aliases') ? $container->get('aliases') : [];
        $rootPath = $aliases['@root'] ?? dirname(__DIR__, 4);

        return new InstallCommand($rootPath);
    },

    TestCommand::class => static function (ContainerInterface $container) {
        return new TestCommand($container->get(Nadi::class));
    },

    VerifyCommand::class => static function (ContainerInterface $container) {
        return new VerifyCommand($container->get(Nadi::class));
    },

    UpdateShipperCommand::class => static function (ContainerInterface $container) {
        $aliases = $container->has('aliases') ? $container->get('aliases') : [];
        $rootPath = $aliases['@root'] ?? dirname(__DIR__, 4);

        return new UpdateShipperCommand($rootPath);
    },
];
