<?php
namespace Recognize\GoogleApiBundle\Utils;

/**
 * Converts between length units
 * Class DistanceConverter
 */
class DistanceConverter {

    // The amount of ... units in ...
    const MILE_IN_KM = 1.609344;
    const KM_IN_MILE = 0.621371192;
    const METERS_IN_KM = 1000;


    public static function convertMetersToMiles( $meters ){
        return self::convertKmToMiles( self::convertMetersToKm( $meters ) );
    }

    public static function convertMetersToKm( $meters ){
        return $meters / self::METERS_IN_KM;
    }


    /**
     * @param number $km
     * @return mixed
     */
    public static function convertKmToMiles( $km ){
        return $km * self::KM_IN_MILE;
    }

    /**
     *
     * @param number $miles
     * @return number
     */
    public static function convertMilesToKm( $miles ){
        return $miles * self::MILE_IN_KM;
    }
}