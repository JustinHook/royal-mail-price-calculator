<?php

namespace RoyalMailPriceCalculator;

use RoyalMailPriceCalculator\Exceptions\UnknownPackageTypeException;

class Calculator
{
    private $now;

    public function __construct()
    {
        $this->now = new \DateTime();
    }

    /**
     * @var Package
     */
    private $package;

    /**
     * @var \RoyalMailPriceCalculator\Services\Service[]
     */
    private $services;

    /**
     * @return Package
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * @param Package $package
     */
    public function setPackage(Package $package)
    {
        $this->package = $package;
    }

    /**
     * @return \RoyalMailPriceCalculator\Services\Service[]
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @param \RoyalMailPriceCalculator\Services\Service[] | \RoyalMailPriceCalculator\Services\Service $services
     */
    public function setServices($services)
    {
        if (is_array($services)) {
            $this->services = $services;
        } else {
            $this->services = array($services);
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function calculatePrice()
    {
        $services = $this->getServices();

        $calculatedPrices = array();

        foreach ($services as $service) {
            $priceData = $service->getPriceData();
            $prices = array();

            try {
                $packageType = $service->getPackageType($this->getPackage());

                foreach ($priceData as $data) {

                    if ($packageType === false) {
                        $packageTypePrices = $data['prices'];
                    } else {
                        $packageTypePrices = $data['prices'][$packageType];
                    }

                    ksort($packageTypePrices);

                    $packagePrice = 0;
                    foreach ($packageTypePrices as $weight => $price) {
                        if ($weight >= $this->getPackage()->getWeight()) {
                            $packagePrice = $price;
                            break;
                        }
                    }
                    $prices[] = array(
                        'price' => number_format($packagePrice, 2, '.', ''),
                        'compensation' => $data['compensation']
                    );


                }
            } catch (UnknownPackageTypeException $e) {
            }

            $calculatedPrices[] = array(
                'service' => $service,
                'prices' => $prices
            );
        }

        return $calculatedPrices;
    }
}
