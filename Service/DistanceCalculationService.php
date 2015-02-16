<?php
namespace Recognize\GoogleApiBundle\Service;

use Recognize\GoogleApiBundle\Entity\LatLng;
use Recognize\GoogleApiBundle\Entity\Location as Location;

class DistanceCalculationService {

    private $apiurl = "http://maps.googleapis.com/maps/api/distancematrix/json?";

    /** @var Location $origin */
    private $origin;

    /** @var Location $destination */
    private $destination;

    /**
     * Calculate the driving distance between two locations
     *
     * @param mixed $origin
     * @param mixed $destination
     * @return int
     */
    public function calculateDistanceInMeters($origin, $destination){

        // Allow both strings, arrays containing lat long pairs and locations
        $this->origin = $this->convertDataToLocation( $origin );
        $this->destination = $this->convertDataToLocation( $destination );


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->generateGoogleApiRequest() );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        $data = json_decode($response);
        if( $data == null ){
            return 0;
        } else {
            return $data->rows[0]->elements[0]->distance->value;
        }
    }

    /**
     * Generate the google api url using
     *
     * @return string
     */
    protected function generateGoogleApiRequest(){
        $url = $this->apiurl;

        if( isset($this->origin) && isset($this->destination) ) {
            $url .= "mode=driving";
            $url .= "&origins=" . $this->origin->toString();
            $url .= "&destinations=" . $this->destination->toString();
            $url .= "&sensor=false";
        }

        return $url;
    }

    /**
     * Convert a string or an array to a location object
     *
     * @param $data
     */
    protected function convertDataToLocation( $data ){

        $loc = new Location();
        if( is_string( $data ) ) {
            $loc = $this->convertStringToLocation( $data );

        // Convert a LatLng pair to a locatioon object
        } else if( is_array( $data ) ){
            $loc = $this->convertArrayToLocation( $data );

        } else if( is_a( $data, 'Recognize\GoogleApiBundle\Entity\Location')) {
            $loc = $data;
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
}