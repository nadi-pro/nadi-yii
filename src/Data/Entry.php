<?php

namespace Nadi\Yii\Data;

use Nadi\Data\Entry as CoreEntry;
use Nadi\Yii\Concerns\InteractsWithMetric;

class Entry extends CoreEntry
{
    use InteractsWithMetric;

    public function __construct(string $type)
    {
        parent::__construct($type);
        $this->registerMetrics();
    }
}
