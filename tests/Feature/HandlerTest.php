<?php

namespace Nadi\Yii\Tests\Feature;

use Nadi\Yii\Handler\HandleExceptionEvent;
use Nadi\Yii\Handler\HandleQueryEvent;
use Nadi\Yii\Nadi;
use Nadi\Yii\Tests\TestCase;

class HandlerTest extends TestCase
{
    public function test_exception_handler_can_be_instantiated(): void
    {
        $config = $this->getNadiConfig();
        $nadi = new Nadi($config);

        $handler = new HandleExceptionEvent($nadi);

        $this->assertInstanceOf(HandleExceptionEvent::class, $handler);
    }

    public function test_query_handler_can_be_instantiated(): void
    {
        $config = $this->getNadiConfig();
        $nadi = new Nadi($config);

        $handler = new HandleQueryEvent($nadi);

        $this->assertInstanceOf(HandleQueryEvent::class, $handler);
    }
}
