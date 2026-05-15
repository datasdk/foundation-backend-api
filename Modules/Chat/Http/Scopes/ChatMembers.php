<?php

namespace Modules\Chat\Http\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait ChatMembers
{
    public function scopeIsMember(Builder $query, int $userId)
    {
  
        return $query->where(function ($q) use ($userId) {
            $q->whereHas('participants', function ($q) use ($userId) {
                $q->where('messageable_id', $userId);
            });
        });
    }
}
