<?php

namespace App\Traits\HasAddresses;

use Lecturize\Addresses\Traits\HasAddresses as OriginalHasAddresses;
use App\Events\AddressSet;
use Lecturize\Addresses\Models\Address;
use Illuminate\Support\Facades\Log;
use Netsells\GeoScope\Traits\GeoScopeTrait;


trait HasAddresses
{
    
    use OriginalHasAddresses;
    use GeoScopeTrait;

    /**
     * Set multiple addresses for the model.
     */
    public function setAddresses(array $addresses): static
    {
        $this->flushAddresses();

        foreach ($addresses as $address) {
            $address['country'] = 'dk';
            $this->setAddress($address);
        }

        return $this;
    }

    /**
     * Replace current address with a new one.
     */
    public function syncAddress(array $data): static
    {
        $this->flushAddresses();
        return $this->setAddress($data);
    }

    /**
     * Add a single address and dispatch event for geocoding.
     */
    public function setAddress(array $data): static
    {
    
        try {

            $data = collect($data)->only([
                'street',
                'city',
                'state',
                'post_code',
                'country_id',
                'note',
                'lat',
                'lng',
                'is_public',
                'is_primary',
                'is_billing',
                'is_shipping',
            ])->toArray();

            
            if(!isset($data['country'])){ $data['country'] = $this->getCountryCodeFromIp(); }


            $address = $this->addAddress($data);

            // 🚀 Dispatch event so listener can handle geocoding
            event(new AddressSet($this, $address));

        } catch (\Throwable $e) {
         
            Log::error('Error in setAddress: ' . $e->getMessage(), [
                'data' => $data,
                'stack' => $e->getTraceAsString(),
            ]);
        }

        return $this;
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable')->withDefault();
    }


    public function countryCode(){

        return $this->country->iso_3166_2; 

    }
    


    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Scope: filter models by distance from coordinates.
     */
    public function scopeWithinDistanceOfAddress($query, float $lat, float $long, float $distance)
    {
        return $query->whereHas('address', function ($q) use ($lat, $long, $distance) {
            if ($lat && $long) {
                $q->withinDistanceOf($lat, $long, $distance);
            }
        });
    }



    /**
     * Hent landekode baseret på IP-adresse.
     *
     * @param string|null $ip Hvis ikke angivet, bruges klientens IP.
     * @return string|null ISO landekode, fx 'DK', eller null hvis ikke fundet.
     */
    private function getCountryCodeFromIp(?string $ip = null): ?string
    {
        try {
            // Brug Laravel request() hvis IP ikke er angivet
            $ip = $ip ?? request()->ip();

            // Hvis localhost, returner DK uden at kalde API
            if (in_array($ip, ['127.0.0.1', '::1'])) {
                return 'DK';
            }

            // ip-api endpoint
            $url = "http://ip-api.com/json/{$ip}?fields=status,countryCode";

            // hent data
            $response = file_get_contents($url);
            $data = json_decode($response, true);

            // tjek om status er success
            if (isset($data['status']) && $data['status'] === 'success') {
                return $data['countryCode'] ?? 'DK';
            }

        } catch (\Throwable $e) {
            \Log::warning('Failed to get country code from IP: ' . $e->getMessage(), [
                'ip' => $ip,
            ]);
        }

        return 'DK';
    }




}
