<?php

namespace App\OutboundPort;

use DateTime;

interface AvailabilityApiInterface
{
    /**
     * @param  string   $origin      The Airport Origin Code
     * @param  string   $destination The Airport Destionation Code
     * @param  DateTime $date        The date of the trip
     * @return string The XML document
     *
     * Fetches the info from the availability price API
     */
    public function fetch(string $origin, string $destination, DateTime $date): string;
}
