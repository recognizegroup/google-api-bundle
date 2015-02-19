<?php
use Recognize\GoogleApiBundle\Entity\DistanceResult;
use Recognize\GoogleApiBundle\Entity\LatLng;
use Recognize\GoogleApiBundle\Entity\Location;
use Recognize\GoogleApiBundle\Service\DistanceCalculationService;

class DistanceCalculationServiceFunctionalTest extends \PHPUnit_Framework_TestCase {

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

    public function testSendingData(){
        $result = $this->service->calculateDistanceInMeters("7607 GX", "7548 RZ");
        $this->assertNotEquals( $result, 0 );

        $results = $this->service->calculateMultipleDistancesInMeters(array("7607 GX"), array("7548 RZ"));
        foreach( $results as $result ){
            /** @var DistanceResult $result */
            $this->assertNotEquals( $result->getDistance(), 0 );
        }

        $results = $this->service->calculateMultipleDistancesInMeters(array("7607 GX"), array("AFSDFWEFDSEFSD"));
        foreach( $results as $result ){
            /** @var DistanceResult $result */
            $this->assertEquals( $result->getDistance(), 0 );
        }

        $resultskm = $this->service->calculateMultipleDistancesInKm(array("7607 GX", "7607 GX"), array("7548 RZ", "7548 RZ"));
        foreach( $resultskm as $result ){
            /** @var DistanceResult $result */
            $this->assertNotEquals( $result->getDistance(), 0 );

            break;
        }
    }

}