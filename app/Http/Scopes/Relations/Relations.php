<?php

namespace App\Http\Scopes\Relations;

trait Relations
{
    public function scopeHasRelations($q, $relation)
    {
        $q->has($relation);
    }
}
