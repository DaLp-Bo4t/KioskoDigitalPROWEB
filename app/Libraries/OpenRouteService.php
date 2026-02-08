<?php

namespace App\Libraries;

use CodeIgniter\Config\Services;

class OpenRouteService
{
    private $apiKey;
    private $baseUrl = 'https://api.openrouteservice.org';

    public function __construct()
    {
        $this->apiKey = getenv('OPENROUTESERVICE_API_KEY');
    }

    /**
     * Geocode an address to [lng, lat] coordinates
     */
    public function geocode($address)
    {
        if (empty($this->apiKey)) {
            return ['error' => 'ORS API Key missing'];
        }

        $client = Services::curlrequest();

        try {
            $response = $client->request('GET', $this->baseUrl . '/geocode/search', [
                'query' => [
                    'api_key' => $this->apiKey,
                    'text' => $address,
                    'size' => 1
                ],
                'headers' => [
                    'Accept' => 'application/json, application/geo+json, application/gpx+xml, text/csv; charset=utf-8'
                ],
                'http_errors' => false
            ]);

            $json = json_decode($response->getBody(), true);

            if (isset($json['features'][0]['geometry']['coordinates'])) {
                return [
                    'lng' => $json['features'][0]['geometry']['coordinates'][0],
                    'lat' => $json['features'][0]['geometry']['coordinates'][1],
                    'label' => $json['features'][0]['properties']['label'] ?? $address
                ];
            }

            return ['error' => 'No se encontraron coordenadas para la dirección', 'details' => $json];

        } catch (\Exception $e) {
            return ['error' => 'Geocoding error: ' . $e->getMessage()];
        }
    }

    /**
     * Get route between origin and destination addresses
     * @param string $origin Origin address
     * @param string $destination Destination address
     * @param array|null $originCoordsOverride Optional [lat, lng] to skip geocoding origin
     * @param array|null $destCoordsOverride Optional [lat, lng] to skip geocoding destination
     */
    public function getRouteAttributes($origin, $destination, $originCoordsOverride = null, $destCoordsOverride = null)
    {
        if (empty($this->apiKey)) {
            return [
                'error' => 'La API Key de OpenRouteService no está configurada',
                'duration' => 'N/A',
                'distance' => 'N/A'
            ];
        }

        // 1. Geocode origin (or use override)
        if ($originCoordsOverride && isset($originCoordsOverride['lat']) && isset($originCoordsOverride['lng'])) {
            $originCoords = [
                'lat' => $originCoordsOverride['lat'],
                'lng' => $originCoordsOverride['lng']
            ];
        } else {
            $originCoords = $this->geocode($origin);
            if (isset($originCoords['error'])) {
                return ['error' => 'Error en origen: ' . $originCoords['error']];
            }
        }

        // 2. Geocode destination (or use override)
        if ($destCoordsOverride && isset($destCoordsOverride['lat']) && isset($destCoordsOverride['lng'])) {
            $destCoords = [
                'lat' => $destCoordsOverride['lat'],
                'lng' => $destCoordsOverride['lng']
            ];
        } else {
            $destCoords = $this->geocode($destination);
            if (isset($destCoords['error'])) {
                return ['error' => 'Error en destino: ' . $destCoords['error']];
            }
        }

        // 2. Get directions
        $client = Services::curlrequest();

        try {
            $response = $client->request('POST', $this->baseUrl . '/v2/directions/driving-car', [
                'headers' => [
                    'Authorization' => $this->apiKey,
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'coordinates' => [
                        [$originCoords['lng'], $originCoords['lat']],
                        [$destCoords['lng'], $destCoords['lat']]
                    ],
                    'language' => 'es'
                ],
                'http_errors' => false
            ]);

            $json = json_decode($response->getBody(), true);

            if (isset($json['routes'][0]['summary'])) {
                $summary = $json['routes'][0]['summary'];
                $durationSeconds = $summary['duration'];
                $distanceMeters = $summary['distance'];

                return [
                    'duration_text' => $this->formatDuration($durationSeconds),
                    'distance_text' => $this->formatDistance($distanceMeters),
                    'duration_seconds' => (int)$durationSeconds,
                    'distance_meters' => (int)$distanceMeters
                ];
            }

            return ['error' => 'No se pudo calcular la ruta', 'details' => $json];

        } catch (\Exception $e) {
            return ['error' => 'Routing error: ' . $e->getMessage()];
        }
    }

    private function formatDuration($seconds)
    {
        // Agregar 10 minutos
        $seconds += 600;
        $minutes = round($seconds / 60);
        return $minutes . ' min';
    }

    private function formatDistance($meters)
    {
        if ($meters >= 1000) {
            return number_format($meters / 1000, 1) . ' km';
        }
        return round($meters) . ' m';
    }
}
