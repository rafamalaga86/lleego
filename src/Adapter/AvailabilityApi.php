<?php

namespace App\Adapter;

use App\OutboundPort\AvailabilityApiInterface;
use DateTime;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AvailabilityApi implements AvailabilityApiInterface
{
    /**
     * @var HttpClientInterface The client to fetch the XML document
     */
    private HttpClientInterface $httpClient;

    /**
     * @var ParameterBagInterface The interface to be used to get the URL from the config variables
     */
    private ParameterBagInterface $parameters;

    /**
     * @var HttpClientInterface $httpClient
     * @var ParameterBagInterface $parameters
     *
     * Constructor
     */
    public function __construct(HttpClientInterface $httpClient, ParameterBagInterface $parameters)
    {
        $this->httpClient = $httpClient;
        $this->parameters = $parameters;
    }

    /**
     * Info in the interface
     */
    public function fetch(string $origin, string $destination, DateTime $date): string
    {
        try {
            $response = $this->httpClient->request(
                'GET',
                $this->parameters->get('availability_api.url'),
                [
                'query' => [
                    'origin' => $origin,
                    'destination' => $destination,
                    'date' => $date->format('Y-m-d'),
                    ]
                ]
            );

            // I'm calling getContent inside the TRY block because responses are lazy and won't throw
            // the exception till getContent is called
            $payload = $response->getContent();
        } catch (Exception $e) {
            // I would handle the error that could arise, for example, invalid status codes (300-500), etc.
        }

        return $payload;
    }
}
