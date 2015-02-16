<?php
namespace Recognize\GoogleApiBundle\Entity;

class Location {

    /** @var string $zipcode */
    private $zipcode;

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
     * Convert the location to a google api url
     * @return string
     */
    public function toString(){
        return str_replace(" ", "+", $this->zipcode );
    }
}