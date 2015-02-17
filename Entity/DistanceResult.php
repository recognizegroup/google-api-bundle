<?php
namespace Recognize\GoogleApiBundle\Entity;

class DistanceResult {

    private $origin;
    private $destination;

    /** @var int $distance */
    private $distance;

    public function __construct( $origin, $destination, $distance ){
        $this->origin = $origin;
        $this->destination = $destination;
        $this->distance = $distance;
    }

    public function getOrigin(){
        return $this->origin;
    }

    public function getDestination(){
        return $this->destination;
    }

    /**
     * Returns distance in meters
     *
     * @return int
     */
    public function getDistance(){
        return $this->distance;
    }

	/**
	 * @param int|float $distance
	 */
	public function setDistance($distance) {
		$this->distance = $distance;
	}

}