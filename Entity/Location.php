<?php
namespace Recognize\GoogleApiBundle\Entity;

use Recognize\GoogleApiBundle\Entity\LatLng;

class Location {

    /** @var string $zipcode */
    private $zipcode

    /** @var /Recognize/GoogleApiBundle/Entity/LatLng */;
    private $latlng;

    /**
     * @param string $zipcode
     */
    public function setZipcode($zipcode){
        $this->zipcode = $zipcode;
    }

    public function getZipcode(){
        return $this->zipcode;
    }

    /**
     * @param LatLng $latlng
     */
    public function setGeoLocation(LatLng $latlng){
        $this->latlng = $latlng;
    }

    /**
     * Convert the location to a google api url
     * @return string
     */
    public function toString(){
        if( isset($this->latlng) ){
            return $this->latlng->getLatitude() . "," . $this->latlng->getLongitude();
        } else {
            return str_replace(" ", "+", $this->zipcode );
        }
    }
}