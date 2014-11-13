<?php
namespace RoyalMailPriceCalculator\Tests;

use RoyalMailPriceCalculator\Services\FirstClassService;
use RoyalMailPriceCalculator\Services\GuaranteedByOnePmService;
use RoyalMailPriceCalculator\Services\SecondClassService;
use RoyalMailPriceCalculator\Package;
use RoyalMailPriceCalculator\Calculator;

class CalculatorTest extends \PHPUnit_Framework_TestCase
{

    public function testGettersAndSetters()
    {

        $package = new Package();
        $package->setWeight(150);

        $calculator = new Calculator();
        $calculator->setPackage($package);

        $this->assertEquals($package, $calculator->getPackage());

    }

    public function testCalculatePrice()
    {
        $calculator = new Calculator();

        $package = new Package();
        $package->setDimensions(15, 15, 0.4);
        $package->setWeight(90);

        $calculator->setPackage($package);

        $expected = array(
            array(
                'service' => new FirstClassService(),
                'prices' => array(
                    array('price' => 0.62, 'compensation' => 20.00)
                )
            )
        );
        $calculator->setServices(new FirstClassService());
        $this->assertEquals($expected, $calculator->calculatePrice());

        $expected = array(
            array(
                'service' => new GuaranteedByOnePmService(),
                'prices' => array(
                    array('price' => 6.40, 'compensation' => 500),
                    array('price' => 7.40, 'compensation' => 1000),
                    array('price' => 9.40, 'compensation' => 2500)
                )
            )
        );
        $calculator->setServices(new GuaranteedByOnePmService());
        $this->assertEquals($expected, $calculator->calculatePrice());

    }

    public function testCalculatePriceWithMultipleServices()
    {
        $calculator = new Calculator();

        $package = new Package();
        $package->setDimensions(15, 15, 0.4);
        $package->setWeight(90);

        $calculator->setPackage($package);

        $expected = array(
            array(
                'service' => new FirstClassService(),
                'prices' => array(
                    array('price' => 0.62, 'compensation' => 20.00)
                )
            ),
            array(
                'service' => new SecondClassService(),
                'prices' => array(
                    array('price' => 0.53, 'compensation' => 20.00)
                )
            )
        );
        $calculator->setServices(array(new FirstClassService(), new SecondClassService()));
        $this->assertEquals($expected, $calculator->calculatePrice());
    }

    public function testCalculatePriceWithUnknownPackageType()
    {
        $calculator = new Calculator();

        $package = new Package();
        $package->setDimensions(15, 15, 0.4);
        $package->setWeight(30000);

        $calculator->setPackage($package);

        $expected = array(
            array(
                'service' => new FirstClassService(),
                'prices' => array()
            ),
            array(
                'service' => new SecondClassService(),
                'prices' => array()
            )
        );
        $calculator->setServices(array(new FirstClassService(), new SecondClassService()));
        $this->assertEquals($expected, $calculator->calculatePrice());
    }
}
