<?php
namespace Recognize\GoogleApiBundle\Entity;

/**
 * Class LatLng
 * @package Recognize\GoogleApiBundle\Entity
 */
class LatLng {

    protected $latitude;
    protected $longitude;

    /**
     * @param $latitude         A string or a number containing the latitude data
     * @param $longitude        A string or a number containing the longitude data
     */
    public function __construct( $latitude, $longitude ){
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getLatitude(){
        return $this->latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude(){
        return $this->longitude;
    }

}