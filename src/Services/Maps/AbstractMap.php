<?php


namespace App\Services\Maps;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Data\MapsDTO;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class AbstractMap
{
    protected $url;
    protected $endpoint;
    protected $client;
    protected $logger;

    function __construct(HttpClientInterface $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    function search($address): MapsDTO
    {
        $this->url  = $this->constructUrl($address);
        try {
            $response = $this->client->request("GET", $this->url);
            return $this->transformDTO($response);
        } catch (TransportExceptionInterface $exception) {
            $this->logger->error('Cannot create request: ' . $exception->getMessage());
            return new MapsDTO();
        }
    }

    abstract protected function transformDTO(ResponseInterface $response): MapsDTO;
    abstract protected function constructUrl($address): string;
}
