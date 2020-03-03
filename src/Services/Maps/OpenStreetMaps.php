<?php


namespace App\Services\Maps;

use App\Data\MapsDTO;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class OpenStreetMaps extends AbstractMap
{
    public function __construct(HttpClientInterface $client, LoggerInterface $logger)
    {
        parent::__construct($client, $logger);
        $this->endpoint = $_ENV['OPEN_STREET_MAPS_ENDPOINT'];
    }

    public function constructUrl($address): string
    {
        return sprintf("%s?format=json&q=%s", $this->endpoint, rawurlencode($address));
    }

    public function transformDTO(ResponseInterface $response): MapsDTO
    {
        $result = $response->toArray(false);

        if(!$result) {
            $this->logger->error('The response object could not be casted to array');
            return new MapsDTO();
        }

        // assumption: I take only the first returned result for a search query
        $result = $result[0];
        $mapsDto = new MapsDTO();

        if(isset($result['lat'])) {
            $mapsDto->latitude = $result['lat'];
        }

        if(isset($result['lat'])) {
            $mapsDto->longitude = $result['lon'];
        }

        if(isset($result['display_name'])) {
            $mapsDto->address = $result['display_name'];
        }

        return $mapsDto;
    }
}
