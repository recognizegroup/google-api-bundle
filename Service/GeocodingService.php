<?php
namespace Recognize\GoogleApiBundle\Service;

use Recognize\ExtraBundle\Service\ContentService;
use Recognize\GoogleApiBundle\Entity\LatLng;
use Recognize\GoogleApiBundle\Entity\Location as Location;

class GeocodingService {

    private $apiurl = "http://maps.googleapis.com/maps/api/geocode/json?";

    /**
     * Finds an address using a latitude-longitude pair
     *
     * @param LatLng $latlng
     * @return Location
     */
    public function findAddressForLatLng(LatLng $latlng){
        $url = $this->apiurl;
        $url .= "latlng=" . $latlng->getLatitude() . "," . $latlng->getLongitude();

        $response = ContentService::getContents( $url, array(),
            array('Content-type: application/json'));

        return $this->parseGeocodingResponse( $response );
    }

    /**
     * Finds a latitude-longitude pair using an address or returns null
     *
     * @param Location $location
     * @return LatLng
     */
    public function findLatLngForAddress(Location $location){
        $url = $this->apiurl;
        $url .= "address=" . $location->toString();

        $response = ContentService::getContents( $url, array(),
            array('Content-type: application/json'));

        $foundlocation = $this->parseGeocodingResponse( $response );
        return $foundlocation->getGeoLocation();
    }

    /**
     * Parses the response and returns the found location
     *
     * @param $jsondata
     * @return Location
     */
    protected function parseGeocodingResponse( $jsondata ){
        $obj = json_decode( $jsondata );

        //var_dump( $jsondata );

        $loc = new Location();
        $loc->setCountry("");
        $loc->setProvince("");
        $loc->setCity("");
        $loc->setZipcode("");
        $loc->setAddress("");
        $loc->setGeoLocation( null );

        if( is_null($obj) == false && $obj->status === "OK" ){

            $result = $obj->results[0];

            // Loop through the address components to build the location object
            if( property_exists($result, "address_components") ){

                $street = "";
                $street_number = "";

                foreach( $result->address_components as $addresscomp){

                    if( in_array("country", $addresscomp->types ) ){
                        $loc->setCountry( $addresscomp->long_name );

                        // Make sure that when a type is found,
                        // it doesn't look through the address component anymore
                        continue;
                    }

                    if( in_array("postal_code", $addresscomp->types ) ){
                        $loc->setZipcode( $addresscomp->long_name );
                        continue;
                    }

                    if( in_array("locality", $addresscomp->types ) ){
                        $loc->setCity( $addresscomp->long_name );
                        continue;
                    }

                    if( in_array("administrative_area_level_1", $addresscomp->types ) ){
                        $loc->setProvince( $addresscomp->long_name );
                        continue;
                    }

                    // Only add the address if both the street and the street number are found
                    if( in_array("route", $addresscomp->types) ){
                        $street = $addresscomp->long_name;

                        if( strlen($street_number) > 0){
                            $loc->setAddress( $street . " " . $street_number );
                        }
                        continue;
                    }

                    if( in_array("street_number", $addresscomp->types) ){
                        $street_number = $addresscomp->long_name;

                        if( strlen($street) > 0){
                            $loc->setAddress( $street . " " . $street_number );
                        }
                        continue;
                    }
                }
            }

            // Add latitude and longitude
            if( property_exists($result, "geometry") && property_exists($result->geometry, "location") ){
                $loc->setGeoLocation( new LatLng( $result->geometry->location->lat, $result->geometry->location->lng ) );
            }
        }

        return $loc;
    }


}