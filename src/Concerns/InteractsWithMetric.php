<?php

namespace Nadi\Yii\Concerns;

use Nadi\Yii\Metric\Application;
use Nadi\Yii\Metric\Framework;
use Nadi\Yii\Metric\Http;
use Nadi\Yii\Metric\Network;

trait InteractsWithMetric
{
    public function registerMetrics(): void
    {
        if (method_exists($this, 'addMetric')) {
            $this->addMetric(new Http);
            $this->addMetric(new Framework);
            $this->addMetric(new Application);
            $this->addMetric(new Network);
        }
    }
}
