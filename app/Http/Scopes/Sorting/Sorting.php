<?php

namespace App\Http\Scopes\Sorting;

use Illuminate\Support\Facades\DB;

trait Sorting
{
    public function scopeSortingById($q, $id)
    {
        if (!empty($id)) {
            if (is_array($id)) {
                $id = implode(",", $id);
            }

            return $q->orderByRaw(DB::raw("FIELD(id, $id)"));
        }
    }
}
