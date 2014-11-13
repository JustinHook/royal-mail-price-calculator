<?php

namespace RoyalMailPriceCalculator\Services;

use RoyalMailPriceCalculator\Package;

abstract class GuaranteedService extends Service
{
    public function getPackageType(Package $package)
    {
        return false;
    }
}
