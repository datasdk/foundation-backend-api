<?php

namespace App\Traits\Slugs;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

trait Slugs
{

    use HasSlug;


    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom($this->sluggable)
            ->saveSlugsTo($this->slugStorageAttribute ?? 'slug');
    }



    public static function findBySlugOrId($slugOrId)
    {
   
        if (is_numeric($slugOrId)) {

            return static::find($slugOrId);

        }

        return static::findBySlug($slugOrId);

    }




/*
    public static function findBySlug($slugOrId, $slugColName = 'slug')
    {

    
        $slugOrId = $slugOrId;
        $lang = app()->getLocale();

        // If the input is already a model instance, return it directly
        if (is_object($slugOrId) && method_exists($slugOrId, 'getTable')) {
            return $slugOrId;
        }

        // If the input is a numeric ID, search for the model by its ID
        if (is_numeric($slugOrId)) {
            return self::find($slugOrId);
        }

        // If the slug column is JSON-based and the slug exists, find the model by the translated slug
        $query = self::query();
        if (self::slugExists($slugOrId, $lang, $slugColName)) {
            return $query->whereJsonContains("$slugColName->$lang", $slugOrId)->first();
        }

        // Fallback to a regular query if the slug is not in JSON format or doesn't match
        return $query->where($slugColName, $slugOrId)->first();
    }

 
    public static function findBySlugOrId($slugOrId, $slugColName = 'slug')
    {
        return self::findBySlug($slugOrId, $slugColName);
    }

 
    protected static function slugExists($slug, $lang, $slugColName = "slug")
    {
        // Ensure the JSON column is queried safely
        try {
            // Check if the slug exists for the given language
            if ($lang) {
                return self::whereJsonContains("$slugColName->$lang", $slug)->exists();
            } else {
                // If no language is specified, check the base slug column
                return self::where($slugColName, $slug)->exists();
            }
        } catch (\Exception $e) {
            // Log the error or handle it if the JSON query fails
            return false;
        }
    }

   
    */
}
