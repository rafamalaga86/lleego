<?php

namespace App\Tests;

use App\Adapter\AvailabilityXMLParser;
use PHPUnit\Framework\TestCase;

class AvailabilityXMLParserTest extends TestCase
{
    public function parseSegmentsToArraysDataProvider()
    {
        $xml1 = <<<EOT
        <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
            <soap:Body>
                <AirShoppingRS xmlns="http://www.iata.org/IATA/EDIST/2017.2"
                               xmlns:ns2="http://www.iberia.com/IATA/NDC/SecurePayment/2017.2" Version="5.000"
                               PrimaryLangID="es">
                    <DataLists>
                        <FlightSegmentList>
                            <FlightSegment SegmentKey="IB044220220601MADBIO">
                                <Departure>
                                    <AirportCode>AGP</AirportCode>
                                    <Date>2022-06-01</Date>
                                    <Time>22:10</Time>
                                    <AirportName>Malaga la bella</AirportName>
                                    <Terminal>
                                        <Name>4</Name>
                                    </Terminal>
                                </Departure>
                                <Arrival>
                                    <AirportCode>BIO</AirportCode>
                                    <Date>2022-06-01</Date>
                                    <Time>23:10</Time>
                                    <ChangeOfDay>0</ChangeOfDay>
                                    <AirportName>Bilbao</AirportName>
                                </Arrival>
                                <MarketingCarrier>
                                    <AirlineID>RY</AirlineID>
                                    <Name>Ryanair</Name>
                                    <FlightNumber>0442</FlightNumber>
                                </MarketingCarrier>
                                <OperatingCarrier>
                                    <AirlineID>RY</AirlineID>
                                    <Name>Ryanair</Name>
                                    <Disclosures>
                                        <Description>
                                            <Text>IB</Text>
                                        </Description>
                                    </Disclosures>
                                </OperatingCarrier>
                                <Equipment>
                                    <AircraftCode>321</AircraftCode>
                                    <Name>Airbus A321</Name>
                                </Equipment>
                                <FlightDetail>
                                    <FlightDuration>
                                        <Value>PT1H</Value>
                                    </FlightDuration>
                                </FlightDetail>
                            </FlightSegment>
                        </FlightSegmentList>
                    </DataLists>
                </AirShoppingRS>
            </soap:Body>
        </soap:Envelope>
EOT;

        $xml2 = <<<EOT
        <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
            <soap:Body>
                <AirShoppingRS xmlns="http://www.iata.org/IATA/EDIST/2017.2"
                               xmlns:ns2="http://www.iberia.com/IATA/NDC/SecurePayment/2017.2" Version="5.000"
                               PrimaryLangID="es">
                    <DataLists>
                        <FlightSegmentList>
                            <FlightSegment SegmentKey="IB044220220601MADBIO">
                                <Departure>
                                    <AirportCode>MAD</AirportCode>
                                    <Date>2022-06-01</Date>
                                    <Time>22:10</Time>
                                    <AirportName>Madrid Adolfo Suarez-Barajas</AirportName>
                                    <Terminal>
                                        <Name>4</Name>
                                    </Terminal>
                                </Departure>
                                <Arrival>
                                    <AirportCode>BIO</AirportCode>
                                    <Date>2022-06-01</Date>
                                    <Time>23:10</Time>
                                    <ChangeOfDay>0</ChangeOfDay>
                                    <AirportName>Bilbao</AirportName>
                                </Arrival>
                                <MarketingCarrier>
                                    <AirlineID>IB</AirlineID>
                                    <Name>Iberia</Name>
                                    <FlightNumber>0442</FlightNumber>
                                </MarketingCarrier>
                                <OperatingCarrier>
                                    <AirlineID>IB</AirlineID>
                                    <Name>Iberia</Name>
                                    <Disclosures>
                                        <Description>
                                            <Text>IB</Text>
                                        </Description>
                                    </Disclosures>
                                </OperatingCarrier>
                                <Equipment>
                                    <AircraftCode>321</AircraftCode>
                                    <Name>Airbus A321</Name>
                                </Equipment>
                                <FlightDetail>
                                    <FlightDuration>
                                        <Value>PT1H</Value>
                                    </FlightDuration>
                                </FlightDetail>
                            </FlightSegment>
                            <FlightSegment SegmentKey="IB044820220601MADBIO">
                                <Departure>
                                    <AirportCode>MAD</AirportCode>
                                    <Date>2022-06-01</Date>
                                    <Time>07:40</Time>
                                    <AirportName>Madrid Adolfo Suarez-Barajas</AirportName>
                                    <Terminal>
                                        <Name>4</Name>
                                    </Terminal>
                                </Departure>
                                <Arrival>
                                    <AirportCode>BIO</AirportCode>
                                    <Date>2022-06-01</Date>
                                    <Time>08:40</Time>
                                    <ChangeOfDay>0</ChangeOfDay>
                                    <AirportName>Bilbao</AirportName>
                                </Arrival>
                                <MarketingCarrier>
                                    <AirlineID>IB</AirlineID>
                                    <Name>Iberia</Name>
                                    <FlightNumber>0448</FlightNumber>
                                </MarketingCarrier>
                                <OperatingCarrier>
                                    <AirlineID>IB</AirlineID>
                                    <Name>Iberia</Name>
                                    <Disclosures>
                                        <Description>
                                            <Text>IB</Text>
                                        </Description>
                                    </Disclosures>
                                </OperatingCarrier>
                                <Equipment>
                                    <AircraftCode>321</AircraftCode>
                                    <Name>Airbus A321</Name>
                                </Equipment>
                                <FlightDetail>
                                    <FlightDuration>
                                        <Value>PT1H</Value>
                                    </FlightDuration>
                                </FlightDetail>
                            </FlightSegment>
                        </FlightSegmentList>
                    </DataLists>
                </AirShoppingRS>
            </soap:Body>
        </soap:Envelope>
EOT;

        $xml3 = <<<EOT
        <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
            <soap:Body>
                <AirShoppingRS xmlns="http://www.iata.org/IATA/EDIST/2017.2"
                               xmlns:ns2="http://www.iberia.com/IATA/NDC/SecurePayment/2017.2" Version="5.000"
                               PrimaryLangID="es">
                    <DataLists>
                        <FlightSegmentList>
                        </FlightSegmentList>
                    </DataLists>
                </AirShoppingRS>
            </soap:Body>
        </soap:Envelope>
EOT;

        $xml4 = 'going to fail';

        return [
            [ // It has ONE segment to find
                $xml1,
                [
                    [
                        'origin-code' => 'AGP',
                        'origin-name' => 'Malaga la bella',
                        'destination-code' => 'BIO',
                        'destination-name' => 'Bilbao',
                        'start' => '2022-06-01 22:10',
                        'end' => '2022-06-01',
                        'transport-number' => '0442',
                        'company-code' => 'RY',
                        'company-name' => 'Ryanair',
                    ],
                ]
            ],
            [ // It has ONE segment to find
                $xml2,
                [
                    [
                        'origin-code' => 'MAD',
                        'origin-name' => 'Madrid Adolfo Suarez-Barajas',
                        'destination-code' => 'BIO',
                        'destination-name' => 'Bilbao',
                        'start' => '2022-06-01 22:10',
                        'end' => '2022-06-01',
                        'transport-number' => '0442',
                        'company-code' => 'IB',
                        'company-name' => 'Iberia',
                    ],
                    [
                        'origin-code' => 'MAD',
                        'origin-name' => 'Madrid Adolfo Suarez-Barajas',
                        'destination-code' => 'BIO',
                        'destination-name' => 'Bilbao',
                        'start' => '2022-06-01 07:40',
                        'end' => '2022-06-01',
                        'transport-number' => '0448',
                        'company-code' => 'IB',
                        'company-name' => 'Iberia',
                    ]
                ]
            ],
            [$xml3, []], // It has ZERO segments to find
        ];
    }

    /**
     * A basic test example with a data provider.
     *
     * @dataProvider parseSegmentsToArraysDataProvider
     */
    public function testParseSegmentsToArrays($xml, $expected): void
    {
        $parser = new AvailabilityXMLParser();
        $segments = $parser->parseSegmentsToArrays($xml);

        $this->assertEquals($segments, $expected);
    }
}
