<?php

use Recognize\GoogleApiBundle\Utils\DistanceConverter;

class DistanceConverterTest extends \PHPUnit_Framework_TestCase {

    public function testTypeConversions(){
        $this->assertEquals(1, DistanceConverter::convertMetersToKm(1000));
        $this->assertEquals(1, DistanceConverter::convertMetersToKm("1000"));
        $this->assertEquals(-1, DistanceConverter::convertMetersToKm(-1000));
        $this->assertEquals(-1.1, DistanceConverter::convertMetersToKm("-1100"));
    }

    public function testUnitConversions(){
        $this->assertEquals(0.621371192, DistanceConverter::convertKmToMiles(1));
        $this->assertEquals(-0.621371192, DistanceConverter::convertKmToMiles(-1));

        $this->assertEquals(1.609344, DistanceConverter::convertMilesToKm(1));
        $this->assertEquals(-1.609344, DistanceConverter::convertMilesToKm(-1));

        $this->assertEquals(0.621371192, DistanceConverter::convertMetersToMiles(1000));
        $this->assertEquals(-0.621371192, DistanceConverter::convertMetersToMiles(-1000));
    }
}