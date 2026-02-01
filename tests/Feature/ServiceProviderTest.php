<?php

namespace Nadi\Yii\Tests\Feature;

use Nadi\Yii\Nadi;
use Nadi\Yii\NadiServiceProvider;
use Nadi\Yii\Tests\TestCase;

class ServiceProviderTest extends TestCase
{
    public function test_service_provider_returns_definitions(): void
    {
        $definitions = NadiServiceProvider::definitions();

        $this->assertIsArray($definitions);
        $this->assertArrayHasKey(Nadi::class, $definitions);
    }

    public function test_nadi_service_can_be_created(): void
    {
        $config = $this->getNadiConfig();
        $nadi = new Nadi($config);

        $this->assertInstanceOf(Nadi::class, $nadi);
        $this->assertTrue($nadi->isEnabled());
    }

    public function test_nadi_service_is_disabled_when_configured(): void
    {
        $config = $this->getNadiConfig(['enabled' => false]);
        $nadi = new Nadi($config);

        $this->assertFalse($nadi->isEnabled());
    }
}
