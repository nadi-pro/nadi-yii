<?php

namespace Nadi\Yii;

use Nadi\Data\Entry;
use Nadi\Data\ExceptionEntry;
use Nadi\Data\Type;
use Nadi\Transporter\Service;

class Nadi
{
    private ?Service $service = null;

    private Transporter $transporter;

    public function __construct(
        private array $config,
    ) {
        $this->transporter = new Transporter($this->config);
        $this->service = $this->transporter->getService();
    }

    public function isEnabled(): bool
    {
        return ($this->config['enabled'] ?? false) && $this->service !== null;
    }

    public function getTransporter(): ?Service
    {
        return $this->service;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function store(Entry|ExceptionEntry $entry): void
    {
        if (! $this->isEnabled() || ! $this->service) {
            return;
        }

        $this->service->handle($entry->toArray());
    }

    public function recordException(\Throwable $exception): void
    {
        $entry = new \Nadi\Yii\Data\ExceptionEntry($exception);
        $this->store($entry);
    }

    public function recordQuery(string $sql, float $duration, string $connectionName = 'default'): void
    {
        $entry = new \Nadi\Yii\Data\Entry(Type::QUERY);
        $entry->content = [
            'connection' => $connectionName,
            'sql' => $sql,
            'duration' => $duration,
            'slow' => $duration >= ($this->config['query']['slow_threshold'] ?? 500),
        ];
        $this->store($entry);
    }

    public function send(): void
    {
        if ($this->service) {
            $this->service->send();
        }
    }

    public function __destruct()
    {
        $this->send();
    }
}
