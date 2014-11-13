<?php

namespace RoyalMailPriceCalculator;

class Package
{
    private $width = 0.00;
    private $length = 0.00;
    private $depth = 0.00;
    private $weight = 0;

    /**
     * @return integer Weight of package in grams
     */
    public function getWeight()
    {
        return $this->weight;
    }


    /**
     * @param integer $weight Weight of package in grams
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @param float $length Length of package in centimeters
     * @param float $width  Width of package in centimeters
     * @param float $depth  Depth of package in centimeters
     */
    public function setDimensions($length, $width, $depth)
    {
        $this->length = $length;
        $this->width = $width;
        $this->depth = $depth;
    }

    /**
     * @return float
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @return float
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @return float
     */
    public function getWidth()
    {
        return $this->width;
    }
}
