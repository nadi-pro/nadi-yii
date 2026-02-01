<?php

namespace Nadi\Yii\Metric;

use Nadi\Metric\Base;
use Nadi\Yii\Support\OpenTelemetrySemanticConventions;

class Http extends Base
{
    public function metrics(): array
    {
        return OpenTelemetrySemanticConventions::httpAttributesFromGlobals();
    }
}
