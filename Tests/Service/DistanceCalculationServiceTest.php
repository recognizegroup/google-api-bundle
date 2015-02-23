<?php
use Recognize\GoogleApiBundle\Entity\DistanceResult;
use Recognize\GoogleApiBundle\Entity\LatLng;
use Recognize\GoogleApiBundle\Entity\Location;
use Recognize\GoogleApiBundle\Service\DistanceCalculationService;

class DistanceCalculationServiceTest extends \PHPUnit_Framework_TestCase {

    /** @var DistanceCalculationService $service */
    protected $service;

    /** @var DistanceResult $defaultnode */
    protected $defaultnode;

    protected $realnode;

    public function setUp(){
        parent::setUp();

        $this->defaultnode = new DistanceResult("7151EM", "7607GX", 0);
        $this->realnode = new DistanceResult("7151EM", "7607GX", 1200);
        $this->service = new DistanceCalculationService();
    }

    public function testConstruct(){
        $apikey = "TESTKEY";
        $service = new DistanceCalculationService(array("api_key" => $apikey, "default_locale" => "en"));
        $emptyservice = new DistanceCalculationService();
        $this->assertNotEquals($emptyservice, $service);

    }

    public function testFaultyResponse(){
        $data = array();
        $data[] = $this->defaultnode;
        $this->service->setOriginsAndDestinations(array("7151EM"), array("7607GX"));

        $this->assertEquals($data, $this->service->parseDistanceResponse( null ) );
        $this->assertEquals($data, $this->service->parseDistanceResponse("") );
        $this->assertEquals($data, $this->service->parseDistanceResponse('{"status": "INVALID REQUEST"}'));
    }

    public function testProperResponse(){
        $data = array();
        $data[] = $this->realnode;
        $this->service->setOriginsAndDestinations(array("7151EM"), array("7607GX"));

        $json = '{"status": "OK", "rows":[{"elements":[{"distance":{"value": 1200}, "status": "OK"}]}]}';
        $this->assertEquals($data, $this->service->parseDistanceResponse($json), "Single origin and destination not parsed properly");

        $secondresult = new DistanceResult("7151EM", array(7.151, -7.607), 1200);
        $data[] = $secondresult;
        $this->service->setOriginsAndDestinations(array("7151EM"), array("7607GX", array(7.151, -7.607)));
        $json = '{"status": "OK", "rows":[{"elements":[{"distance":{"value": 1200}, "status": "OK"},
        {"distance":{"value": 1200}, "status": "OK"}]}]}';
        $this->assertEquals($data, $this->service->parseDistanceResponse($json), "Single origin and multiple destinations not parsed properly" );


        $loc = new Location();
        $loc->setGeoLocation(new LatLng(7.151, -7.607));

        $data = array();
        $data[] = $this->realnode;
        $data[] = new DistanceResult("7151EM", $loc, 1200);
        $data[] = $this->realnode;
        $data[] = new DistanceResult("7151EM", $loc, 1200);
        $this->service->setOriginsAndDestinations(array("7151EM", "7151EM"), array("7607GX", $loc));
        $json = '{"status": "OK", "rows":[{"elements":[{"distance":{"value": 1200}, "status": "OK"},
        {"distance":{"value": 1200}, "status": "OK"}]},{"elements":[{"distance":{"value": 1200}, "status": "OK"},
        {"distance":{"value": 1200}, "status": "OK"}]}]}';
        $this->assertEquals($data, $this->service->parseDistanceResponse($json), "Multiple origins and destinations not parsed properly" );
    }
}