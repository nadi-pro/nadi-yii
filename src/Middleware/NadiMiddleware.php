<?php

namespace Nadi\Yii\Middleware;

use Nadi\Yii\Handler\HandleHttpRequestEvent;
use Nadi\Yii\Nadi;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class NadiMiddleware implements MiddlewareInterface
{
    private HandleHttpRequestEvent $httpHandler;

    public function __construct(
        private Nadi $nadi,
        private array $config,
    ) {
        $this->httpHandler = new HandleHttpRequestEvent($this->nadi, $this->config);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (! $this->nadi->isEnabled()) {
            return $handler->handle($request);
        }

        $startTime = microtime(true);

        try {
            $response = $handler->handle($request);
        } catch (\Throwable $e) {
            $this->nadi->recordException($e);

            throw $e;
        }

        $this->httpHandler->handle($request, $response);

        return $response;
    }
}
