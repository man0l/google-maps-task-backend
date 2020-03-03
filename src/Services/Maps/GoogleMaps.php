<?php


namespace App\Services\Maps;

use App\Data\MapsDTO;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;


class GoogleMaps extends AbstractMap
{
    const STATUS_OK = 'OK';
    private $apiKey;

    function __construct(HttpClientInterface $client, LoggerInterface $logger)
    {
        parent::__construct($client, $logger);
        $this->apiKey   = $_ENV['GOOGLE_MAPS_GEOCODE_API_KEY'];
        $this->endpoint = $_ENV['GOOGLE_MAPS_API_ENDPOINT'];
    }

    protected function transformDTO(ResponseInterface $response): MapsDTO
    {
        $mapsDto = new MapsDTO();
        $result = $response->toArray(false);

        if(!$result) {
            $this->logger->error('The response object could not be casted to array');
            return new MapsDTO();
        }

        if($result['status'] !== static::STATUS_OK) {
            $this->logger->error('The status of the response is not OK' . $result['status']);
            return new MapsDTO();
        }

        // take only the first result
        $result = $result['results'][0];

        if(isset($result['formatted_address'])) {
            $mapsDto->address = $result['formatted_address'];
        }

        if(isset($result['geometry'])) {
            $mapsDto->latitude  = $result['geometry']['location']['lat'];
            $mapsDto->longitude = $result['geometry']['location']['lng'];
        }

        return $mapsDto;
    }

    protected function constructUrl($address): string {
        return sprintf(
            "%s?address=%s&key=%s",
            $this->endpoint,
            rawurlencode($address),
            $this->apiKey
        );
    }
}
