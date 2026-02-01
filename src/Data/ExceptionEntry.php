<?php

namespace Nadi\Yii\Data;

use Nadi\Data\ExceptionEntry as CoreExceptionEntry;
use Nadi\Yii\Concerns\InteractsWithMetric;

class ExceptionEntry extends CoreExceptionEntry
{
    use InteractsWithMetric;

    public function __construct(\Throwable $exception)
    {
        parent::__construct($exception);
        $this->registerMetrics();
    }
}
