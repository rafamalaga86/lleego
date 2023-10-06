<?php

namespace App\Core\InboundPort;

use App\Core\Entity\Segment;
use DateTime;

interface SegmentServiceInterface
{
    /**
     * @param  string   $origin      The Airport Origin Code
     * @param  string   $destination The Airport Destionation Code
     * @param  DateTime $date        The date of the trip
     * @return string The JSON string
     *
     * Given the origin destination and date, give backs all the segments in json format
     */
    public function getSegmentsInJson(string $origin, string $destination, DateTime $date): string;

    /**
     * @param  string   $origin      The Airport Origin Code
     * @param  string   $destination The Airport Destionation Code
     * @param  DateTime $date        The date of the trip
     * @return array Gives back the segments in an array
     *
     * Given the origin destination and date, give back the segments in array format
     */
    public function getSegmentsInArray(string $origin, string $destination, DateTime $date): array;
}
