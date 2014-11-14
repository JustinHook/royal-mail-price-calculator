<?php

namespace RoyalMailPriceCalculator\Tests\Services;

use RoyalMailPriceCalculator\Services\DomesticService;
use RoyalMailPriceCalculator\Services\FirstClassService;
use RoyalMailPriceCalculator\Tests\Fixtures\TestService;
use RoyalMailPriceCalculator\Package;

class FirstClassServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider packageTypeProvider
     * @param $length
     * @param $width
     * @param $depth
     * @param $weight
     * @param $expected
     */
    public function testPackageType($length, $width, $depth, $weight, $expected)
    {
        $service = new FirstClassService();

        $package = new Package();
        $package->setDimensions($length, $width, $depth);
        $package->setWeight($weight);

        $this->assertEquals($expected, $service->getPackageType($package));

    }

    public function packageTypeProvider()
    {
        return array(
            array(24, 16.5, 0.5, 100, DomesticService::LETTER),
            array(35.3, 25, 2.5, 750, DomesticService::LARGE_LETTER),
            array(45, 35, 16, 2000, DomesticService::SMALL_PARCEL),
            array(61, 46, 46, 20000, DomesticService::MEDIUM_PARCEL)
        );
    }

    /**
     * @expectedException     \RoyalMailPriceCalculator\Exceptions\UnknownPackageTypeException
     */
    public function testUnknownPackageTypeException()
    {
        $service = new FirstClassService();

        $package = new Package();
        $package->setDimensions(100, 100, 100);
        $package->setWeight(20000);

        $service->getPackageType($package);
    }

    /**
     * @expectedException     \Exception
     */
    public function testNoPriceDataFile()
    {
        $service = new TestService();
        $service->getPriceData();
    }

    public function testJsonSerialization()
    {
        $this->assertEquals('"Test Service"', json_encode(new TestService()));
    }
}
