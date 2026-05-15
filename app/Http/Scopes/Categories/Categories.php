<?php

namespace App\Http\Scopes\Categories;

trait Categories
{
    public function scopeType($q, $type)
    {
        if (!is_array($type)) {
            $type = [$type];
        }

        return $q->whereIn("type", $type);
    }

    public function scopeOnlyRoot($q)
    {
        return $q->whereNull("parent_id")->orWhere("parent_id", 0);
    }

    public function scopeWhereId($q, $id)
    {
        return $q->orWhereIn("id", [$id]);
    }

    public function scopeChildrenAndSelf($q, $ids = null, $ignore = null)
    {
        if ($ids) {
            $ids = collect($ids)->map(function ($id) {
                if (!$c = self::find($id)) {
                    return [];
                }

                return $c->descendantsAndSelf($id)->toFlatTree()->pluck('id')->toArray();
            })->flatten()->toArray();

            return $q->orWhereIn("id", $ids)->sortingById($ids);
        }
    }

    public function scopeHasCategory($q, $id)
    {
        return $this->scopeCategory($q, $id);
    }

    public function scopeCategory($query, $value)
    {
        if (empty($value)) {
            return $query;
        }

        if (is_string($value)) {
            $value = explode(',', $value);
        }

        $locale = app()->getLocale();

        return $query->whereHas('categories', function ($q) use ($value, $locale) {
            $q->where(function ($q) use ($value, $locale) {
                $q->whereIn('categories.id', $value);

                foreach ($value as $v) {
                    $q->orWhere("categories.slug->{$locale}", $v);
                    $q->orWhere('categories.slug', $v);
                }
            });
        });
    }

    public function scopeCategories($q, $idArray)
    {
        return $this->scopeCategory($q, $idArray);
    }
}
