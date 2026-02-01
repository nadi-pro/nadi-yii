<?php

namespace Nadi\Yii\Tests\Feature;

use Nadi\Yii\Middleware\NadiMiddleware;
use Nadi\Yii\Middleware\OpenTelemetryMiddleware;
use Nadi\Yii\Nadi;
use Nadi\Yii\Tests\TestCase;

class MiddlewareTest extends TestCase
{
    public function test_nadi_middleware_can_be_instantiated(): void
    {
        $config = $this->getNadiConfig();
        $nadi = new Nadi($config);

        $middleware = new NadiMiddleware($nadi, $config);

        $this->assertInstanceOf(NadiMiddleware::class, $middleware);
    }

    public function test_otel_middleware_can_be_instantiated(): void
    {
        $config = $this->getNadiConfig();
        $nadi = new Nadi($config);

        $middleware = new OpenTelemetryMiddleware($nadi);

        $this->assertInstanceOf(OpenTelemetryMiddleware::class, $middleware);
    }
}
