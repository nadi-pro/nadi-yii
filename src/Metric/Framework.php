<?php

namespace Nadi\Yii\Metric;

use Nadi\Metric\Base;

class Framework extends Base
{
    public function metrics(): array
    {
        return [
            'framework.name' => 'yii',
            'framework.version' => $this->getYiiVersion(),
            'service.name' => $_ENV['APP_NAME'] ?? 'yii-app',
            'service.version' => $_ENV['APP_VERSION'] ?? '1.0.0',
        ];
    }

    protected function getYiiVersion(): string
    {
        // Yii 3 uses individual package versions
        $composerLock = dirname(__DIR__, 5) . '/composer.lock';
        if (file_exists($composerLock)) {
            $lock = json_decode(file_get_contents($composerLock), true);
            foreach ($lock['packages'] ?? [] as $package) {
                if ($package['name'] === 'yiisoft/yii-http') {
                    return $package['version'] ?? 'unknown';
                }
            }
        }

        return 'unknown';
    }
}
