<?php

namespace Nadi\Yii\Metric;

use Nadi\Metric\Base;

class Application extends Base
{
    public function metrics(): array
    {
        $metrics = [
            'app.environment' => $this->getEnvironment(),
        ];

        if (defined('YII_ENV')) {
            $metrics['app.environment'] = YII_ENV;
        }

        return $metrics;
    }

    protected function getEnvironment(): string
    {
        return $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? 'production';
    }
}
