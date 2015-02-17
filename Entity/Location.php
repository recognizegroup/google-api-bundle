<?php
namespace Recognize\GoogleApiBundle\Entity;

use Recognize\GoogleApiBundle\Entity\LatLng;

class Location {

    /** @var LatLng */
    private $latlng;

    /** @var string $country */
    private $country;

    /** @var string $province */
    private $province;

    /** @var string $city */
    private $city;

    /** @var string $zipcode */
    private $zipcode;

    /** @var string $address */
    private $address;

    /**
     * @param string $zipcode
     */
    public function setCountry($country){
        $this->country = $country;
    }

    /**
     * @param string $province
     */
    public function setProvince($province){
        $this->province = $province;
    }

    /**
     * @param string $city
     */
    public function setCity($city){
        $this->city = $city;
    }

    /**
     * @param string $zipcode
     */
    public function setZipcode($zipcode){
        $this->zipcode = $zipcode;
    }

    /**
     * @param string $address
     */
    public function setAddress($address){
        $this->address = $address;
    }

    /**
     * @param mixed $latlng
     */
    public function setGeoLocation($latlng){
        $this->latlng = $latlng;
    }

    /**
     * @return string
     */
    public function getCountry(){
        return $this->country;
    }

    /**
     * @return string
     */
    public function getProvince(){
        return $this->province;
    }

    /**
     * @return string
     */
    public function getCity(){
        return $this->city;
    }

    /**
     * @return string
     */
    public function getZipcode(){
        return $this->zipcode;
    }

    /**
     * @return string
     */
    public function getAddress(){
        return $this->address;
    }

    /**
     * @return mixed
     */
    public function getGeoLocation(){
        return $this->latlng;
    }

    /**
     * Convert the location to a google api url
     * @return string
     */
    public function toString(){
        if( isset($this->latlng) ){
            return $this->latlng->getLatitude() . "," . $this->latlng->getLongitude();
        } else {
            $string = "";
            if( is_null($this->address) == false ){
                $string .= $this->address;
            }

            if( is_null($this->city) == false ){
                if( strlen($string) > 0 && substr($string, -1) !== "," ){
                    $string .= ",";
                }

                $string .= $this->city;
            }

            if( is_null($this->province) == false ){
                if( strlen($string) > 0 && substr($string, -1) !== "," ){
                    $string .= ",";
                }

                $string .= $this->province;
            }

            if( is_null($this->zipcode) == false ){
                if( strlen($string) > 0 && substr($string, -1) !== "," ){
                    $string .= ",";
                }

                $string .= $this->zipcode;
            }

            if( is_null($this->country) == false ){
                if( strlen($string) > 0 && substr($string, -1) !== "," ){
                    $string .= ",";
                }

                $string .= $this->country;
            }

            // Replace all the spaces with + signs
            $string = str_replace(" ", "+", $string);
            return preg_replace('/  +/', '+', $string);
        }
    }
}