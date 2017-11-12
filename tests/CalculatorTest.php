<?php
namespace RoyalMailPriceCalculator\Tests;

use PHPUnit\Framework\TestCase;
use RoyalMailPriceCalculator\Services\FirstClassService;
use RoyalMailPriceCalculator\Services\GuaranteedByOnePmService;
use RoyalMailPriceCalculator\Services\SecondClassService;
use RoyalMailPriceCalculator\Package;
use RoyalMailPriceCalculator\Calculator;
use RoyalMailPriceCalculator\Tests\Fixtures\TestService;

class CalculatorTest extends TestCase
{

    public function testGettersAndSetters()
    {
        $service = new TestService();

        $calculator = new Calculator();
        $calculator->setServices($service);

        $this->assertEquals(array($service), $calculator->getServices());
    }

    public function testCalculatePrice()
    {
        $calculator = new Calculator();
        $usePriceOn = new \DateTime('2015-03-30');

        $package = new Package();
        $package->setDimensions(15, 15, 0.4);
        $package->setWeight(90);

        $expected = array(
            array(
                'service' => new FirstClassService($usePriceOn),
                'prices' => array(
                    array('price' => 0.63, 'compensation' => 20.00)
                )
            )
        );
        $calculator->setServices(new FirstClassService($usePriceOn));
        $this->assertEquals($expected, $calculator->calculatePrice($package));

        $expected = array(
            array(
                'service' => new GuaranteedByOnePmService($usePriceOn),
                'prices' => array(
                    array('price' => 6.45, 'compensation' => 500),
                    array('price' => 7.45, 'compensation' => 1000),
                    array('price' => 9.45, 'compensation' => 2500)
                )
            )
        );
        $calculator->setServices(new GuaranteedByOnePmService($usePriceOn));
        $this->assertEquals($expected, $calculator->calculatePrice($package));

    }

    public function testCalculatePriceWithMultipleServices()
    {
        $calculator = new Calculator();
        $usePriceOn = new \DateTime('2015-03-01');

        $package = new Package();
        $package->setDimensions(15, 15, 0.4);
        $package->setWeight(90);

        $expected = array(
            array(
                'service' => new FirstClassService($usePriceOn),
                'prices' => array(
                    array('price' => 0.62, 'compensation' => 20.00)
                )
            ),
            array(
                'service' => new SecondClassService($usePriceOn),
                'prices' => array(
                    array('price' => 0.53, 'compensation' => 20.00)
                )
            )
        );
        $calculator->setServices(array(new FirstClassService($usePriceOn), new SecondClassService($usePriceOn)));
        $this->assertEquals($expected, $calculator->calculatePrice($package));
    }

    public function testCalculatePriceWithUnknownPackageType()
    {
        $calculator = new Calculator();
        $usePriceOn = new \DateTime('2015-03-01');

        $package = new Package();
        $package->setDimensions(15, 15, 0.4);
        $package->setWeight(30000);

        $expected = array(
            array(
                'service' => new FirstClassService($usePriceOn),
                'prices' => array()
            ),
            array(
                'service' => new SecondClassService($usePriceOn),
                'prices' => array()
            )
        );
        $calculator->setServices(array(new FirstClassService($usePriceOn), new SecondClassService($usePriceOn)));
        $this->assertEquals($expected, $calculator->calculatePrice($package));
    }
}
