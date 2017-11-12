Royal Mail Price Calculator
===========================
[![Build Status](https://travis-ci.org/JustinHook/royal-mail-price-calculator.svg)](https://travis-ci.org/JustinHook/royal-mail-price-calculator)

This library allows you to calculator the cost of sending a package with Royal Mail.

**Includes prices valid from March 2017.**

Usage
-----
Install the latest version with `composer require justinhook/royal-mail-price-calculator`


Supported Services
------------------
Service  | Class
------------- | -------------
1st Class  | `FirstClassService()`
2nd Class  | `SecondClassService()`
Signed For 1st Class | `SignedForFirstClassService()`
Signed For 2nd Class | `SignedForSecondClassService()`
Guaranteed by 9am | `GuaranteedByNineAmService()`
Guaranteed by 9am with Saturday Guarantee | `GuaranteedByNineAmWithSaturdayService()`
Guaranteed by 1pm | `GuaranteedByOnePmService()`
Guaranteed by 1pm with Saturday Guarantee | `GuaranteedByOnePmWithSaturdayService()`

Example
-------
```php
<?php

require 'vendor/autoload.php';

use \RoyalMailPriceCalculator\Calculator;
use \RoyalMailPriceCalculator\Package;
use \RoyalMailPriceCalculator\Services\GuaranteedByOnePmService;
use \RoyalMailPriceCalculator\Services\FirstClassService;

$calculator = new Calculator();

$package = new Package();
$package->setDimensions(15, 15, 0.4);
$package->setWeight(90);

$calculator->setServices(array(new FirstClassService(), new GuaranteedByOnePmService()));

foreach ($calculator->calculatePrice($package) as $calculated)
{
    echo $calculated['service']->getName() . "\n";
    foreach ($calculated['prices'] as $price) {
        echo "  →  £{$price['price']} (Compensation: £{$price['compensation']})\n";
    }
    echo "\n";
}
```

Will output:
```
1st Class Service
  →  £0.62 (Compensation: £20)

Guaranteed by 1pm
  →  £6.40 (Compensation: £500)
  →  £7.40 (Compensation: £1000)
  →  £9.40 (Compensation: £2500)
```
