<?php

namespace RoyalMailPriceCalculator\Services;

use Doctrine\Common\Inflector\Inflector;
use RoyalMailPriceCalculator\Package;
use Symfony\Component\Yaml\Yaml;

abstract class Service implements \JsonSerializable
{
    private $priceOnDate;
    private $priceDataDir;
    protected $name;

    public function __construct(\DateTime $priceOnDate = null)
    {
        if (is_null($priceOnDate)) {
            $this->priceOnDate = new \DateTime();
        } else {
            $this->priceOnDate = $priceOnDate;
        }
        $this->priceDataDir = __DIR__ . '/../PriceData/';
    }

    abstract public function getPackageType(Package $package);

    /**
     * @return string
     */
    private function getPriceDataFileName()
    {
        $name = join('', array_slice(explode('\\', get_class($this)), -1));
        return Inflector::tableize($name);
    }

    /**
     * @return string
     */
    private function getPriceDataFilePath()
    {
        $pricesFilename = $this->getPriceDataFileName();

        $iterator = new \DirectoryIterator($this->priceDataDir);

        $latest = null;

        foreach ($iterator as $file) {
            if ($file->isDir() && !$file->isDot()) {
                $date = \DateTime::createFromFormat('Ymd', $file->getFilename());
                $date->setTime(0, 0, 0);
                if ($date > $latest && $date <= $this->priceOnDate) {
                    $latest = clone $date;
                }
            }
        }

        return $this->priceDataDir . $latest->format('Ymd') . '/' . $pricesFilename . '.yml';
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getPriceData()
    {
        $priceData = $this->getPriceDataFilePath();
        if (!file_exists($priceData)) {
            throw new \Exception("Price data file not found at $priceData");
        }
        return Yaml::parse(file_get_contents($priceData));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->getName();
    }
}
