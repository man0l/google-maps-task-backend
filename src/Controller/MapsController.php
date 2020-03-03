<?php

namespace App\Controller;

use App\Services\Maps\OpenStreetMaps;
use App\Services\Maps\GoogleMaps;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MapsController extends AbstractController
{
    /**
     * @Route("/maps/google/{address}", name="maps_google")
     */
    public function mapsGoogle($address, GoogleMaps $googleMaps)
    {
        $mapsDto = $googleMaps->search($address);

        return $this->json([
           'longitude' => $mapsDto->longitude,
           'latitude' => $mapsDto->latitude,
            'address' => $mapsDto->address
        ]);
    }

    /**
     * @Route("/maps/openstreet/{address}", name="maps_openstreet")
     */
    public function mapsOpenStreet($address, OpenStreetMaps $openStreetMaps)
    {
        $mapsDto = $openStreetMaps->search($address);

        return $this->json([
            'longitude' => $mapsDto->longitude,
            'latitude' => $mapsDto->latitude,
            'address' => $mapsDto->address
        ]);
    }
}
