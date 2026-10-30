<?php

namespace App\Managers;

use InvalidArgumentException;
use Lunar\Managers\PaymentManager as BasePaymentManager;

class PaymentManager extends BasePaymentManager
{
    /**
     * Create a new driver instance with graceful fallback for unmapped drivers
     * so that the admin order view/edit pages never crash.
     *
     * @param string $driver
     * @return mixed
     */
    protected function createDriver($driver)
    {
        try {
            return parent::createDriver($driver);
        } catch (InvalidArgumentException $e) {
            // Graceful fallback to offline driver so Filament Admin never crashes on unknown or legacy drivers
            $type = config("lunar.payments.types.{$driver}", []);
            $fallback = $this->createOfflineDriver();
            return $fallback->setConfig($type);
        }
    }
}
