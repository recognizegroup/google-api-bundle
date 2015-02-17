<?php
use Recognize\GoogleApiBundle\Entity\DistanceResult;
use Recognize\GoogleApiBundle\Entity\LatLng;
use Recognize\GoogleApiBundle\Entity\Location;
use Recognize\GoogleApiBundle\Service\DistanceCalculationService;
use Recognize\GoogleApiBundle\Service\GeocodingService;

class GeocodingServiceTest extends \PHPUnit_Framework_TestCase {

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

    public function testFaultyAddressParsing(){
        $this->assertEquals($this->emptyloc, $this->service->parseGeocodingResponse(null));
        $this->assertEquals($this->emptyloc, $this->service->parseGeocodingResponse(""));
        $this->assertEquals($this->emptyloc, $this->service->parseGeocodingResponse('{"status":"INVALID REQUEST"}'));

        $json = '{"status":"OK", "results":[{}]}';
        $this->assertEquals($this->emptyloc, $this->service->parseGeocodingResponse($json) );
    }

    public function testProperAddressParsing(){
        $json = '{"status":"OK", "results":[{"geometry":{"location":{"lat":51.1515, "lng": -20.052}}}]}';
        $testloc = new Location();
        $testloc->setCountry("");
        $testloc->setProvince("");
        $testloc->setCity("");
        $testloc->setZipcode("");
        $testloc->setAddress("");
        $testloc->setGeoLocation(new LatLng(51.1515, -20.052) );
        $this->assertEquals($testloc, $this->service->parseGeocodingResponse($json) );

        $json = '{"status":"OK", "results":[
        {"address_components":[{"long_name": "The Netherlands", "types":["country"]}],
        "geometry":{"location":{"lat":51.1515, "lng": -20.052}}}]}';
        $testloc->setCountry("The Netherlands");
        $this->assertEquals($testloc, $this->service->parseGeocodingResponse($json), "Country not retrieved from JSON" );

        $json = '{"status":"OK", "results":[
        {"address_components":[
            {"long_name": "Almelo", "types":["locality"]},
            {"long_name": "The Netherlands", "types":["country"]}
        ],
        "geometry":{"location":{"lat":51.1515, "lng": -20.052}}}]}';
        $testloc->setCity("Almelo");
        $this->assertEquals($testloc, $this->service->parseGeocodingResponse($json), "City not retrieved from JSON" );

        $json = '{"status":"OK", "results":[
        {"address_components":[
            {"long_name": "Almelo", "types":["locality"]},
            {"long_name": "7607GX", "types":["postal_code"]},
            {"long_name": "The Netherlands", "types":["country"]}
        ],
        "geometry":{"location":{"lat":51.1515, "lng": -20.052}}}]}';
        $testloc->setZipcode("7607GX");
        $this->assertEquals($testloc, $this->service->parseGeocodingResponse($json), "Zipcode not retrieved from JSON" );

        $json = '{"status":"OK", "results":[
        {"address_components":[
            {"long_name": "Almelo", "types":["locality"]},
            {"long_name": "7607GX", "types":["postal_code"]},
            {"long_name": "Overijssel", "types":["administrative_area_level_1"]},
            {"long_name": "The Netherlands", "types":["country"]}
        ],
        "geometry":{"location":{"lat":51.1515, "lng": -20.052}}}]}';
        $testloc->setProvince("Overijssel");
        $this->assertEquals($testloc, $this->service->parseGeocodingResponse($json), "Province not retrieved from JSON" );

        $json = '{"status":"OK", "results":[
        {"address_components":[
            {"long_name": "Almelo", "types":["locality"]},
            {"long_name": "7607GX", "types":["postal_code"]},
            {"long_name": "Overijssel", "types":["administrative_area_level_1"]},
            {"long_name": "Stationsstraat", "types":["route"]},
            {"long_name": "The Netherlands", "types":["country"]}
        ],
        "geometry":{"location":{"lat":51.1515, "lng": -20.052}}}]}';
        $this->assertEquals($testloc, $this->service->parseGeocodingResponse($json), "Address added dispite that it was incomplete ( no street number )" );

        $json = '{"status":"OK", "results":[
        {"address_components":[
            {"long_name": "Almelo", "types":["locality"]},
            {"long_name": "7607GX", "types":["postal_code"]},
            {"long_name": "Overijssel", "types":["administrative_area_level_1"]},
            {"long_name": "11", "types":["street_number"]},
            {"long_name": "The Netherlands", "types":["country"]}
        ],
        "geometry":{"location":{"lat":51.1515, "lng": -20.052}}}]}';
        $this->assertEquals($testloc, $this->service->parseGeocodingResponse($json), "Address added dispite that it was incomplete ( street )" );

        $json = '{"status":"OK", "results":[
        {"address_components":[
            {"long_name": "Almelo", "types":["locality"]},
            {"long_name": "7607GX", "types":["postal_code"]},
            {"long_name": "Overijssel", "types":["administrative_area_level_1"]},
            {"long_name": "Stationsstraat", "types":["route"]},
            {"long_name": "11", "types":["street_number"]},
            {"long_name": "The Netherlands", "types":["country"]}
        ],
        "geometry":{"location":{"lat":51.1515, "lng": -20.052}}}]}';
        $testloc->setAddress("Stationsstraat 11");
        $this->assertEquals($testloc, $this->service->parseGeocodingResponse($json), "Address not retrieved from JSON" );
    }
}