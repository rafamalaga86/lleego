<?php

namespace App\OutboundPort;

interface AvailabilityXMLParserInterface
{
    /**
     * @param  string $xml A string containing the whole XML SOAP document
     * @return array
     *
     * Takes the XML document and gives back the segments
     */
    public function parseSegmentsToArrays(string $xmlString): array;
}
