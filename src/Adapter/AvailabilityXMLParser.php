<?php

namespace App\Adapter;

use App\OutboundPort\AvailabilityXMLParserInterface;
use SimpleXMLElement;
use Symfony\Component\DomCrawler\Crawler;

class AvailabilityXMLParser implements AvailabilityXMLParserInterface
{
    /**
     * @param  string $xmlString A string containing the XML
     * @return array The extracted segments
     *
     * Extracts the segments from the XML document
     */
    public function parseSegmentsToArrays(string $xmlString): array
    {
        $result = [];
        $soap = simplexml_load_string($xmlString);
        $response = $soap->xpath('/soap:Envelope/soap:Body')[0]->AirShoppingRS->DataLists->FlightSegmentList;
        $json = json_encode($response);
        $array = json_decode($json, true);

        foreach ($array['FlightSegment'] as $segment) {
            $result[] = [
                'origin-code' => $segment['Departure']['AirportCode'],
                'origin-name' => $segment['Departure']['AirportName'],
                'destination-code' => $segment['Arrival']['AirportCode'],
                'destination-name' => $segment['Arrival']['AirportName'],
                'start' => $segment['Departure']['Date'] . ' ' . $segment['Departure']['Time'],
                'end' => $segment['Arrival']['Date'], // Apparently, from the arrival we only need the date
                'transport-number' => $segment['MarketingCarrier']['FlightNumber'],
                'company-code' => $segment['OperatingCarrier']['AirlineID'],
                'company-name' => $segment['OperatingCarrier']['Name'],
            ];
        }
        return $result;
    }
}
