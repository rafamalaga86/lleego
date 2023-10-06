<?php

namespace App\Core\Service;

use App\Core\Entity\Segment;
use App\Core\InboundPort\SegmentServiceInterface;
use App\OutboundPort\AvailabilityApiInterface;
use App\OutboundPort\AvailabilityXMLParserInterface;
use DateTime;

class SegmentService implements SegmentServiceInterface
{
    /**
     * The api to fetch the data from
     *
     * @var \AvailabilityApiInterface
     */
    protected AvailabilityApiInterface $api;

    /**
     * The parser to fetch the data fetched
     *
     * @var \AvailabilityApiInterface
     */
    protected AvailabilityXMLParserInterface $parser;

    /**
     * @param AvailabilityApiInterface       $api    The api to fetch the data from
     * @param AvailabilityXMLParserInterface $parser The parser to fetch the data fetched
     *
     *                                               The constructor
     */
    public function __construct(
        AvailabilityApiInterface $api,
        AvailabilityXMLParserInterface $parser
    ) {
        $this->api = $api;
        $this->parser = $parser;
    }

    /**
     * @param  $array The array from which build a Segment
     * @return Segment The segment created
     *
     * Creates a Segment from an Array
     */
    protected function createFromArray($array): Segment
    {
        $segment = new Segment();

        $segment->setOriginCode($array['origin-code'])
            ->setOriginName($array['origin-name'])
            ->setDestinationCode($array['destination-code'])
            ->setDestinationName($array['destination-name'])
            ->setStart(new DateTime($array['start']))
            ->setEnd(new DateTime($array['end']))
            ->setTransportNumber($array['transport-number'])
            ->setCompanyCode($array['company-code'])
            ->setCompanyName($array['company-name']);

        return $segment;
    }

    /**
     * @param  Segment The segment to serialize
     * @return $array The array built from the segment
     *
     * Creates a Segment from an Array
     */
    protected function serialize(Segment $segment): array
    {
        return [
            'originCode' => $segment->getOriginCode(),
            'originName' => $segment->getOriginName(),
            'destinationCode' => $segment->getDestinationCode(),
            'destinationName' => $segment->getDestinationName(),
            // I am using "h" for hour format because that makes it coincide with the result in the exercise PDF,
            // but normally I would use "H" instead to make it 24hours clock
            'start' => $segment->getStart()->format('Y-m-d h:i'),
            'end' => $segment->getEnd()->format('Y-m-d'),
            'transportNumber' => $segment->getTransportNumber(),
            'companyCode' => $segment->getCompanyCode(),
            'companyName' => $segment->getCompanyName(),
        ];
    }

    /**
     * @param  string   $origin      The Airport Origin Code
     * @param  string   $destination The Airport Destionation Code
     * @param  DateTime $date        The date of the trip
     * @return Segment[]
     *
     * Given the origin destination and date, give backs all the segments in an array
     */
    protected function getSegments(string $origin, string $destination, DateTime $date): array
    {
        $segments = [];
        $xml = $this->api->fetch($origin, $destination, $date);
        $arraySegments = $this->parser->parseSegmentsToArrays($xml);
        foreach ($arraySegments as $array) {
            $segments[] = $this->createFromArray($array);
        }

        return $segments;
    }

    /**
     * Info in the interface
     */
    public function getSegmentsInArray(string $origin, string $destination, DateTime $date): array
    {
        $segmentsArray = [];
        $segments = $this->getSegments($origin, $destination, $date);

        foreach ($segments as $segment) {
            $segmentsArray[] = $this->serialize($segment);
        }

        return $segmentsArray;
    }


    /**
     * Info in the interface
     */
    public function getSegmentsInJson(string $origin, string $destination, DateTime $date): string
    {
        $segmentsArray = $this->getSegmentsInArray($origin, $destination, $date);

        return json_encode($segmentsArray);
    }
}
