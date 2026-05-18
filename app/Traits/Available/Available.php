<?php

namespace App\Traits\Available;

use App\Models\Available as Availability;
use Carbon\Carbon;

trait Available
{
    /**
     * Get the related availability model.
     */
    public function availability()
    {
        return $this->morphTo();
    }

    /**
     * Get the one-to-one availability relationship.
     */
    public function available()
    {
        return $this->morphOne(Availability::class, "availability")->withDefault([
            'from' => null,
            'to' => null
        ]);
    }

    /**
     * Set the availability for a model (either always available or within a date range).
     *
     * @param string|null $from
     * @param string|null $to
     * @return $this
     */
    public function set_available(array $dates)
    {
        $from = $dates['from'] ?? null;
        $to   = $dates['to'] ?? null;
        

        // Check if always available (both from and to are empty)
        $always_available = empty($from) && empty($to);
            
        $params = ['always_available' => $always_available];
        

        if($from){

            $params["from"] = self::convertToDate($from);

        }


        if($to){

            $params["to"] = self::convertToDate($to);

        }
        

        // Update or create the availability record
        $this->available()->updateOrCreate(
            [
                'availability_id'   => $this->id,
                'availability_type' => get_class($this),
            ],
            $params
        );

        return $this;
    }


    public function set_available_from($from){

        return $this->set_available([
            "from" => $from,
        ]);

    }


    public function set_available_to($to){

        return $this->set_available([
            "to" => $to,
        ]);
        
    }

    /**
     * Scope to check availability now (or in general).
     *
     * @param Builder $q
     * @return Builder
     */
    public function scopeAvailable($q)
    {
        return $q->whereHas("available", function ($q) {
            $q->where(function ($query) {
                $query->where('from', '<=', now())
                    ->where('to', '>', now());
            })->orWhere(function ($query) {
                $query->where('from', '<=', now())
                    ->whereNull('to');
            })->orWhere(function ($query) {
                $query->whereNull('from')
                    ->where('to', '<', now());
            });
        });
    }

    /**
     * Scope to check availability from a specific date.
     *
     * @param Builder $q
     * @param string|null $from
     * @return Builder
     */
    public function scopeAvailableFrom($q, $from = null)
    {
        return $this->scopeAvailableRange($q, 'from', '>=', $from);
    }

    /**
     * Scope to check availability up to a specific date.
     *
     * @param Builder $q
     * @param string $to
     * @return Builder
     */
    public function scopeAvailableTo($q, $to)
    {
        return $this->scopeAvailableRange($q, 'to', '<=', $to);
    }

    /**
     * Scope to check availability at a specific date.
     *
     * @param Builder $q
     * @param string $date
     * @return Builder
     */
    public function scopeAvailableAt($q, $date)
    {
        return $q->whereHas("available", function ($q) use ($date) {
            $q->where('from', '<=', Carbon::parse($date))
                ->where('to', '>=', Carbon::parse($date));
        });
    }

    /**
     * Scope to check availability at a specific date (for date comparison only).
     *
     * @param Builder $q
     * @param string $date
     * @return Builder
     */
    public function scopeAvailableAtDate($q, $date)
    {
        return $q->whereHas("available", function ($q) use ($date) {
            $q->whereDate('from', '<=', Carbon::parse($date))
                ->whereDate('to', '>=', Carbon::parse($date));
        });
    }

    /**
     * Scope to check availability between a date range (including time).
     *
     * @param Builder $query
     * @param string $from
     * @param string $to
     * @return Builder
     */
    public function scopeAvailableBetween($query, string $from, string $to)
    {
        return $query->whereHas('available', function ($query) use ($from, $to) {
            $query->where('from', '<=', Carbon::parse($to))
                ->where('to', '>=', Carbon::parse($from));
        });
    }

    /**
     * Scope to check availability between a date range (for date comparison only).
     *
     * @param Builder $query
     * @param string $from
     * @param string $to
     * @return Builder
     */
    public function scopeAvailableBetweenDate($query, string $from, string $to)
    {
        return $query->whereHas('available', function ($query) use ($from, $to) {
            $query->whereDate('from', '<=', Carbon::parse($to))
                ->whereDate('to', '>=', Carbon::parse($from));
        });
    }

    /**
     * Convert a string to a Carbon instance, or return null if the string is empty.
     *
     * @param string|null $date
     * @return Carbon|null
     */
    private static function convertToDate($date)
    {
        if (!$date) {
            return null;
        }

        // Hvis det allerede er en Carbon-instans, returnér som det er
        if ($date instanceof \Carbon\Carbon) {
         
            return $date;
        }
  
        // Hvis det er en dato-streng eller noget andet, forsøg at parse det
        return \Carbon\Carbon::parse($date);
    }


    /**
     * Generalized scope for availability range checks.
     *
     * @param Builder $query
     * @param string $field
     * @param string $operator
     * @param string $date
     * @return Builder
     */
    private function scopeAvailableRange($query, $field, $operator, $date)
    {
        return $query->whereHas('available', function ($q) use ($field, $operator, $date) {
            $q->where(function ($q) use ($field, $operator, $date) {
                $q->whereNull($field)
                    ->orWhere($field, $operator, Carbon::parse($date));
            });
        });
    }
}
