<?php


namespace App\Tests\Services\Maps;
use App\Data\MapsDTO;
use App\Services\Maps\GoogleMaps;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class GoogleMapsTest extends WebTestCase
{
    private $logger;
    private $client;
    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        self::bootKernel();
        $container = self::$container;
        $this->logger = $container->get('monolog.logger');
        $this->client = $container->get('http_client');
    }

    /**
     * @dataProvider mapsDataProvider
     */
    public function testGetCoordinates($address, $latitude, $longitude) {
        $googleMaps = new GoogleMaps($this->client, $this->logger);
        $mapsDto = $googleMaps->search($address);

        self::assertInstanceOf(MapsDTO::class, $mapsDto);
        self::assertObjectHasAttribute('address', $mapsDto);
        self::assertObjectHasAttribute('longitude', $mapsDto);
        self::assertObjectHasAttribute('latitude', $mapsDto);
        self::assertEquals($mapsDto->address, $address);
        self::assertEquals($mapsDto->latitude, $latitude);
        self::assertEquals($mapsDto->longitude, $longitude);
    }

    public function mapsDataProvider() {
        return [
          ['133 E Jackson St Rm. 107, Burnet, TX 78611, USA', 30.7564279,  -98.2264033],
          ['1 Hacker Way, Menlo Park, CA 94025, USA', 37.485134, -122.1483749],
          ['Barcelona, Spain', 41.3850639,  2.1734035],
        ];
    }
}
