<?php
namespace Recognize\GoogleApiBundle\Service;

use Recognize\ExtraBundle\Service\ContentService;
use Recognize\GoogleApiBundle\Entity\DistanceResult;
use Recognize\GoogleApiBundle\Entity\LatLng;
use Recognize\GoogleApiBundle\Entity\Location as Location;
use Recognize\GoogleApiBundle\Utils\DistanceConverter;

class DistanceCalculationService {

    private $apiurl = "http://maps.googleapis.com/maps/api/distancematrix/json?";

    /** @var Location[] $origin */
    private $origins;

    /** @var Location[] $destinations */
    private $destinations;

    /** @var mixed[] $destinationsinput */
    private $destinationsinput;

    /** @var mixed[] $destinationsinput */
    private $originsinput;

    /**
     * Calculate the driving distance between multiple locations
     *
     * @param string|array $origins
     * @param string|array $destinations
     * @return DistanceResult[]
     */
    public function calculateMultipleDistancesInMeters($origins, $destinations){
		$origins = (!is_array($origins)) ? array($origins) : $origins;

        // Allow both strings, arrays containing lat long pairs and locations
        $this->origins = $this->convertDataToLocations( $origins );
        $this->originsinput = $origins;
        $this->destinations = $this->convertDataToLocations( $destinations );
        $this->destinationsinput = $destinations;

        $response = ContentService::getContents( $this->generateGoogleApiRequest(), array(),
            array('Content-type: application/json'));

        return $this->parseDistanceResponse( $response );
    }

	/**
	 * @param string|array $origins
	 * @param string|array $destinations
	 * @return DistanceResult[]
	 */
	public function calculateMultipleDistancesInKm($origins, $destinations) {
		$results = $this->calculateMultipleDistancesInMeters($origins, $destinations);
		foreach($results as &$result) {
			$result->setDistance(DistanceConverter::convertMetersToKm($result->getDistance()));
		}
		return $results;
	}

    /**
     * Calculate the driving distance between two locations
     *
     * @param mixed $origin
     * @param mixed $destination
     * @return int
     */
    public function calculateDistanceInMeters($origin, $destination){

        // Allow both strings, arrays containing lat long pairs and locations
        $this->origins = array( $this->convertDataToLocation( $origin ) );
        $this->originsinput = array( $origin );
        $this->destinations = array( $this->convertDataToLocation( $destination ) );
        $this->destinationsinput = array( $destination );

        $response = ContentService::getContents( $this->generateGoogleApiRequest(), array(),
            array('Content-type: application/json'));

		$results = $this->parseDistanceResponse( $response );
        return $results[0]->getDistance();
    }

    /**
     * Generate the google api url using
     *
     * @return string
     */
    protected function generateGoogleApiRequest(){
        $url = $this->apiurl;

        if( isset($this->origins) && isset($this->destinations) ) {
            $url .= "mode=driving";
            $url .= "&origins=";
            for ($i = 0, $length = count($this->origins); $i < $length; $i++){
                $url .= $this->origins[ $i ]->toString();

                // Add pipes between all the origins
                if( $i + 1 < $length) {
                    $url .= "|";
                }
            }
            $url .= "&destinations=";
            for ($i = 0, $length = count($this->destinations); $i < $length; $i++){
                $url .= $this->destinations[ $i ]->toString();

                // Add pipes between all the destinations
                if( $i + 1 < $length) {
                    $url .= "|";
                }
            }

            $url .= "&sensor=false";
        }

        return $url;
    }

    /**
     * Convert an array of location strings, latlong pairs to an array of Location objects
     *
     * @param $locations
     * @return Location[]
     */
    protected function convertDataToLocations( $locations ){
        $locs = array();

        for( $i = 0, $length = count($locations); $i < $length; $i++) {
            $locs[] = $this->convertDataToLocation( $locations[$i] );
        }

        return $locs;
    }

    /**
     * Convert a string or an array to a location object
     *
     * @param $location
     * @return Location
     */
    protected function convertDataToLocation( $location ){

        $loc = new Location();
        if( is_string( $location ) ) {
            $loc = $this->convertStringToLocation( $location );

        // Convert a LatLng pair to a locatioon object
        } else if( is_array( $location ) ){
            $loc = $this->convertArrayToLocation( $location );

        } else if( is_a( $location, 'Recognize\GoogleApiBundle\Entity\Location')) {
            $loc = $location;
        }

        return $loc;
    }

    /**
     * Convert a string to a location
     *
     * @param $string
     * @return Location
     */
    protected function convertStringToLocation( $string ){
        $loc = new Location();
        $loc->setZipcode( $string );
        return $loc;
    }

    /**
     * Convert a string to a location
     *
     * @param $string
     * @return Location
     */
    protected function convertArrayToLocation( $latlngpair ){
        $loc = new Location();
        $latlng = new LatLng( $latlngpair[0], $latlngpair[1]);
        $loc->setGeoLocation( $latlng );
        return $loc;
    }

    /**
     * Parses a string response and returns an array of distance results
     *
     * @param $jsondata
     * @return DistanceResult[]
     */
    protected function parseDistanceResponse( $jsondata ){
        $obj = json_decode( $jsondata );

        $returndistances = array();
        $defaultnode = new DistanceResult( $this->originsinput[0], $this->destinationsinput[0], 0 );

        // Add default nodes if there is an error
        if( $obj == null ){
            $returndistances[] = $defaultnode;
        } else {
            if( $obj->status !== "OK") {
                $returndistances[] = $defaultnode;
            } else {

                // Loop through the results
                for($j = 0, $jlength = count($obj->rows); $j < $jlength; $j++ ){
                    $row = $obj->rows[ $j ];
                    for( $i = 0, $length = count($row->elements); $i < $length; $i++ ) {
                        $element = $row->elements[$i];

                        if ($element->status === "OK") {
                            $node = new DistanceResult($this->originsinput[$j],
                                $this->destinationsinput[$i],
                                $element->distance->value);

                        // Return a distance of 0 if the zipcode wasn't found
                        } else {
                            $node = new DistanceResult($this->originsinput[$j],
                                $this->destinationsinput[$i],
                                0);
                        }
                        $returndistances[] = $node;
                    }

                }

            }
        }

        return $returndistances;
    }
}