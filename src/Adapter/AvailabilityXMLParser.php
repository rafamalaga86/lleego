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

        $response = $soap
            ->xpath('/soap:Envelope/soap:Body')[0]
            ->AirShoppingRS
            ->DataLists
            ->FlightSegmentList
            ->children();
        $json = json_encode($response);
        $array = json_decode($json, true);

        // If no Segment is found, return empty array
        if (!$response->count()) {
            return [];
        }

        // If there is only one item, the previous lines would delete the outer array of FlightSegment
        // and this would break the parsing, we have to correct that
        if ($response->count() === 1) {
            $array['FlightSegment'] = [$array['FlightSegment']];
        }

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
