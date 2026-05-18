<?php

namespace App\Traits\Active;

use Illuminate\Database\Eloquent\Builder;

trait Active
{
    /**
     * Boot the trait and apply the global scope for the active status.
     */
    public static function booted()
    {
        // Check if 'withInactive' is not present in the request
        if (!request()->boolean("withInactive")) {
            // Add a global scope to only include active items by default
            static::addGlobalScope('active', function (Builder $builder) {
                $builder->where('active', true); // Ensure only active records are included
            });
        }
    }

    // Uncomment the following methods if you wish to use them as custom query scopes.

    /*
    /**
     * Scope a query to only include active records.
     *
     * @param Builder $q
     * @return Builder
     */
    public function scopeActive(Builder $q)
    {
        return $q->where('active', true);
    }

    /**
     * Scope a query to only include inactive records.
     *
     * @param Builder $q
     * @return Builder
     */
    public function scopeInactive(Builder $q)
    {
        return $q->where('inactive', false);
    }
  
}
