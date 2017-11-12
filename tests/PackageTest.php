<?php

namespace RoyalMailPriceCalculator\Tests;

use PHPUnit\Framework\TestCase;
use RoyalMailPriceCalculator\Package;

class PackageTest extends TestCase
{
    public function testSettersAndGetters()
    {
        $package = new Package();
        $package->setDimensions(5, 10, 15);

        $this->assertEquals(5, $package->getLength());
        $this->assertEquals(10, $package->getWidth());
        $this->assertEquals(15, $package->getDepth());

        $package->setWeight(150);
        $this->assertEquals(150, $package->getWeight());
    }
}
