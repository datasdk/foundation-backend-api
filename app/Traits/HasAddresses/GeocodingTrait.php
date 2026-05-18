<?php

namespace App\Traits\HasAddresses;

use GuzzleHttp\Client;
use Netsells\GeoScope\Traits\GeoScopeTrait;
use Illuminate\Support\Facades\Log;

trait GeocodingTrait
{
    use GeoScopeTrait;

    // Exposed scopes for geo-location related queries
    protected $exposedScopes = [
        "WithinDistanceOf",
        "OrderByDistanceFrom"
    ];

    /**
     * Retrieve latitude and longitude based on address components.
     *
     * @param string $address    Full address (street name and number)
     * @param string $city       City
     * @param string $postalCode Postal code
     * @param string $country    Country
     * @return array|null        ['latitude' => float, 'longitude' => float] or null on failure
     */
    public function getCoordinates(string $address, string $city, string $postalCode, string $country): ?array
    {
        $fullAddress = trim("$address, $city, $postalCode, $country");
    
        return $this->getCoordinatesByFullAddress($fullAddress);
    }

    /**
     * Retrieve latitude and longitude based on a full address string.
     *
     * @param string $fullAddress Full address as a single string.
     * @return array|null         ['latitude' => float, 'longitude' => float] or null on failure
     */
    public function getCoordinatesByFullAddress(string $fullAddress): ?array
    {
        $encodedAddress = trim(urlencode($fullAddress));

        // Google API key
        $apiKey = trim(config("GOOGLE_MAPS_API_KEY") ?? env('GOOGLE_MAPS_API_KEY'));

        if (!$apiKey) {
            Log::warning("No Google Maps API key configured.");
            return null;
        }

        // Geocoding API URL
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$encodedAddress&key=$apiKey";

        // HTTP client
        $client = new Client();

        try {
            // Send request to the Google Geocoding API
            $response = $client->get($url);
            $data = json_decode($response->getBody(), true);

            // If the status is OK, return the coordinates
            if ($data['status'] === 'OK') {
                $location = $data['results'][0]['geometry']['location'];

                return [
                    'latitude' => $location['lat'],
                    'longitude' => $location['lng'],
                ];

            }

            // Log error if no results were found
            Log::notice("Geocoding failed: No results found for address: $fullAddress", [
                'status' => $data['status']
            ]);

            return null;

        } catch (\Exception $e) {

       
            // Log error if the API request fails
            Log::alert("Geocoding API request failed for address: $fullAddress", [
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }

    /**
     * Get region based on postal code.
     *
     * @param int $postalCode Postal code
     * @return string|null    The region name or null if no match
     */
    public function getRegionByPostalCode($postalCode): ?string
    {
        $regions = [
            "københavn" => [
                ['min' => 1000, 'max' => 2999] // Copenhagen and surrounding areas
            ],
            "sjælland" => [
                ['min' => 3000, 'max' => 3999], // Northern Zealand
                ['min' => 4000, 'max' => 4999]  // Southern Zealand and other parts of Zealand
            ],
            "fyn" => [
                ['min' => 5000, 'max' => 5999] // Funen and surrounding islands
            ],
            "sønderjylland" => [
                ['min' => 6000, 'max' => 6999] // Southern Jutland
            ],
            "midtjylland" => [
                ['min' => 7000, 'max' => 7999], // Central Jutland
                ['min' => 8000, 'max' => 8999]  // Aarhus and surrounding areas
            ],
            "nordjylland" => [
                ['min' => 9000, 'max' => 9999] // Aalborg and Northern Jutland
            ],
            "bornholm" => [
                ['min' => 3700, 'max' => 3799] // Bornholm
            ]
        ];

        // Check each region's postal code range
        foreach ($regions as $region => $ranges) {
            foreach ($ranges as $range) {
                if ($postalCode >= $range['min'] && $postalCode <= $range['max']) {
                    return $region;
                }
            }
        }

        return null; // If no match was found
    }
}
