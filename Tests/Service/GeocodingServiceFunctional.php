<?php
use Recognize\GoogleApiBundle\Entity\DistanceResult;
use Recognize\GoogleApiBundle\Entity\LatLng;
use Recognize\GoogleApiBundle\Entity\Location;
use Recognize\GoogleApiBundle\Service\DistanceCalculationService;
use Recognize\GoogleApiBundle\Service\GeocodingService;

class GeocodingServiceFunctionalTest extends \PHPUnit_Framework_TestCase {

    /** @var GeocodingService $service */
    protected $service;

    /** @var Location $emptyloc */
    protected $emptyloc;

    public function setUp(){
        parent::setUp();

        $this->service = new GeocodingService();

        $emptyloc = new Location();
        $emptyloc->setCountry("");
        $emptyloc->setProvince("");
        $emptyloc->setCity("");
        $emptyloc->setZipcode("");
        $emptyloc->setAddress("");
        $emptyloc->setGeoLocation(null);
        $this->emptyloc = $emptyloc;
    }

    public function testSendingData(){
        $location = new Location();
        $location->setAddress("Stationsstraat 11");
        $location->setZipcode("7607 GX");
        $location->setProvince("Overijssel");
        $location->setCity("Almelo");
        $location->setCountry("Netherlands");

        $latlng = $this->service->findLatLngForAddress( $location );
        $this->assertFalse( is_null($latlng) );

        $location->setGeoLocation( $latlng );
        $newlocation = $this->service->findAddressForLatLng( $latlng );
        $this->assertEquals($newlocation, $location);
    }

    public function testSendingLocalizedData(){
        $translatedservice = new GeocodingService(array("default_locale" => "nl", "api_key" => null));

        $location = $translatedservice->findAddressForLatLng( new LatLng(52.3583890,6.6565540) );

        $this->assertEquals( $location->getCountry(), "Nederland", "Address data not localized properly" );
    }
}