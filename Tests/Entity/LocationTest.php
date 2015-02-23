<?php
use Recognize\GoogleApiBundle\Entity\LatLng;
use Recognize\GoogleApiBundle\Entity\Location;

class LocationTest extends \PHPUnit_Framework_TestCase {

    public function testGeolocationToString() {
        $location = new Location();
        $location->setGeoLocation( new LatLng(1.2222, 23.4434) );
        $this->assertEquals( "1.2222,23.4434", $location->toString());

        $location->setGeoLocation( new LatLng(-15.3345, "0.234") );
        $this->assertEquals("-15.3345,0.234", $location->toString());
    }

    public function testAddressToString(){
        $location = new Location();

        $location->setAddress("Stationsstraat 11");
        $this->assertEquals("Stationsstraat+11", $location->toString());

        $location->setCity("Almelo");
        $this->assertEquals("Stationsstraat+11,Almelo", $location->toString());

        $location->setCountry("The                   Netherlands");
        $this->assertEquals("Stationsstraat+11,Almelo,The+Netherlands", $location->toString());

        $location->setProvince("Overijssel");
        $this->assertEquals("Stationsstraat+11,Almelo,Overijssel,The+Netherlands", $location->toString());

        $location->setZipcode("7607 GX");
        $this->assertEquals("Stationsstraat+11,Almelo,Overijssel,7607+GX,The+Netherlands", $location->toString());
    }
}