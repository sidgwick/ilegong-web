<?php
/**
 * Coordinate Implementation
 *
 * PHP version 5.3
 *
 * @category  Location
 * @author    Marcus T. Jaschen <mjaschen@gmail.com>
 * @copyright 2013 r03.org
 * @license   http://www.opensource.org/licenses/mit-license MIT License
 * @link      http://r03.org/
 */

namespace LocationHelper;

use LocationHelper\Ellipsoid,
    LocationHelper\Distance,
    LocationHelper\Distance\DistanceInterface,
    LocationHelper\Formatter\Coordinate\FormatterInterface;

/**
 * Coordinate Implementation
 *
 * @category Location
 * @author   Marcus T. Jaschen <mjaschen@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license MIT License
 * @link     http://r03.org/
 */
class Coordinate
{
    /**
     * @var float
     */
    protected $lat;

    /**
     * @var float
     */
    protected $lng;

    /**
     * @var Ellipsoid
     */
    protected $ellipsoid;

    static  $EARTH_RADIUS = 6371;//地球半径，平均半径为6371km

    /**
     * @param float     $lat       -90.0 .. +90.0
     * @param float     $lng       -180.0 .. +180.0
     * @param Ellipsoid $ellipsoid if omitted, WGS-84 is used
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($lat, $lng, Ellipsoid $ellipsoid = null)
    {
        if (! $this->isValidLatitude($lat)) {
            throw new \InvalidArgumentException("Latitude value must be numeric -90.0 .. +90.0 (given: {$lat})");
        }

        if (! $this->isValidLongitude($lng)) {
            throw new \InvalidArgumentException("Longitude value must be numeric -180.0 .. +180.0 (given: {$lng})");
        }

        $this->lat = doubleval($lat);
        $this->lng = doubleval($lng);

        if ($ellipsoid !== null) {
            $this->ellipsoid = $ellipsoid;
        } else {
            $this->ellipsoid = Ellipsoid::createDefault();
        }
    }

    /**
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @return float
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * @return Ellipsoid
     */
    public function getEllipsoid()
    {
        return $this->ellipsoid;
    }

    /**
     * Calculates the distance between the given coordinate
     * and this coordinate.
     *
     * @param Coordinate        $coordinate
     * @param DistanceInterface $calculator instance of distance calculation class
     *
     * @return float
     */
    public function getDistance(Coordinate $coordinate, DistanceInterface $calculator)
    {
        return $calculator->getDistance($this, $coordinate);
    }

    /**
     * @param FormatterInterface $formatter
     *
     * @return mixed
     */
    public function format(FormatterInterface $formatter)
    {
        return $formatter->format($this);
    }

    /**
     * @param Coordinate $coordinate
     * @param float $distance
     * @return array
     */
    public function getSquarePoint(Coordinate $coordinate, $distance = 0.5) {
        $lat = $coordinate->getLat();
        $lng = $coordinate->getLng();
        $dlng = 2 * asin(sin($distance / (2 * self::$EARTH_RADIUS)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);
        $dlat = $distance / self::$EARTH_RADIUS;
        $dlat = rad2deg($dlat);
        return array(
            'left-top' => array('lat' => $lat + $dlat, 'lng' => $lng - $dlng),
            'right-top' => array('lat' => $lat + $dlat, 'lng' => $lng + $dlng),
            'left-bottom' => array('lat' => $lat - $dlat, 'lng' => $lng - $dlng),
            'right-bottom' => array('lat' => $lat - $dlat, 'lng' => $lng + $dlng)
        );

    }

    /**
     * Validates latitude
     *
     * @param mixed $latitude
     *
     * @return bool
     */
    protected function isValidLatitude($latitude)
    {
        return $this->isNumericInBounds($latitude, - 90.0, 90.0);
    }

    /**
     * Validates longitude
     *
     * @param mixed $longitude
     *
     * @return bool
     */
    protected function isValidLongitude($longitude)
    {
        return $this->isNumericInBounds($longitude, -180.0, 180.0);
    }

    /**
     * Checks if the given value is (1) numeric, and (2) between lower
     * and upper bounds (including the bounds values).
     *
     * @param float $value
     * @param float $lower
     * @param float $upper
     *
     * @return bool
     */
    protected function isNumericInBounds($value, $lower, $upper)
    {
        if (! is_numeric($value)) {
            return false;
        }

        if ($value < $lower || $value > $upper) {
            return false;
        }

        return true;
    }
}
