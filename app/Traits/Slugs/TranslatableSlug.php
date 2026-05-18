<?php

namespace App\Traits\Slugs;

use Spatie\Sluggable\SlugOptions;
use Spatie\Sluggable\HasTranslatableSlug;

trait TranslatableSlug
{
    use HasTranslatableSlug;


    /**
     * Slug options til Spatie
     */
    public function getSlugOptions(): SlugOptions
    {


if($this::class == "Modules\Companies\Models\Companies"){

  //  dd($this->slugStorageAttribute);

}
    
        return SlugOptions::create()
            ->generateSlugsFrom($this->slugSource())
            ->usingSeparator('-')
            ->preventOverwrite()
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo($this->slugStorageAttribute ?? 'slug');
    }

    /**
     * Felt der bruges til at generere slug fra
     */
    public function slugSource(): string
    {

        if (property_exists($this, 'sluggable')) {

            return is_array($this->sluggable) ? $this->sluggable[0] : $this->sluggable;

        }

        return 'name';

    }


    /**
     * Find model via ID eller slug
     */
    public static function findBySlugOrId($value)
    {

        if (is_numeric($value)) {

            return static::find($value);

        }

        return static::findBySlug($value);


    }

   
}
