<?php

namespace App\Traits\Language;

use Spatie\Translatable\HasTranslations as BaseHasTranslations;

trait Language
{
    // Use Spatie's HasTranslations trait for basic translation functionality
    use BaseHasTranslations;

    // Static variable for storing language preferences
    public static $lang;

    /**
     * Translate the model's attributes based on the provided language.
     * 
     * @param string $lang The language to translate to (default is "default", which uses the app's current locale).
     * @return $this
     */
    public function translate($lang = "default")
    {
        // If no valid language is provided, return the model as is
        if (!$lang || $lang === "false" || $lang === "0" || $lang === "null") {
            return $this;
        }

        // Use the app's current locale if "default" is specified
        if ($lang === "default") {
            $lang = app()->getLocale();
        }

        // Translate the model's attributes using the translateDeep method
        return $this->translateDeep(parent::toArray(), $lang);
    }

    /**
     * Recursively translate all values in an array (and nested arrays) that are translatable.
     *
     * @param mixed  $data The data to translate (could be an array or object).
     * @param string $lang The language to translate to.
     * @return mixed The translated data.
     */
    protected function translateDeep($data, string $lang)
    {
        // Get the application's default language
        $defaultLang = app()->getLocale();
        // Get available locales from the config
        $locales = config('app.locales', []);
        
        // FØJET: Hent oversættelige felter fra Spatie trait
        $translatableAttributes = $this->getTranslatableAttributes();

        // If the data is an array...
        if (is_array($data)) {
            // Check if the array is a "translatable array" – i.e., has keys matching a language code
            $keys = array_keys($data);
            $isTranslatable = (bool) count(array_intersect($keys, $locales));

            if ($isTranslatable) {
                // Return the translation in the desired language or fallback
                if (isset($data[$lang])) {
                    return $data[$lang];
                } elseif (isset($data[$defaultLang])) {
                    return $data[$defaultLang];
                } elseif (isset($data['en'])) {
                    return $data['en'];
                } else {
                    // If none of the expected keys exist, return the first value
                    return reset($data);
                }
            } else {
                // If it's not a translatable array, recursively iterate over each item
                $translated = [];
                foreach ($data as $key => $value) {
                    // FØJET: Hvis dette er et oversætteligt felt, håndter det specielt
                    if (in_array($key, $translatableAttributes) && is_array($value)) {
                        // Dette er et Spatie oversættelsesfelt (konverteret fra JSON til array)
                        $translated[$key] = $this->getFieldTranslation($value, $lang, $defaultLang);
                    } else {
                        $translated[$key] = $this->translateDeep($value, $lang);
                    }
                }
                return $translated;
            }
        }

        // If the data is an object with a toArray() method (e.g., an Eloquent model), translate its array representation
        if (is_object($data) && method_exists($data, 'toArray')) {
            return $this->translateDeep($data->toArray(), $lang);
        }

        // If the data is not an array or object, return the value as is
        return $data;
    }

    /**
     * FØJET: Hjælpemetode til at hente oversættelse af et felt
     *
     * @param array  $translationArray Oversættelsesarrayet (f.eks. {"da": "tekst", "en": "text"})
     * @param string $lang Ønsket sprog
     * @param string $defaultLang Standard sprog
     * @return string
     */
    protected function getFieldTranslation(array $translationArray, string $lang, string $defaultLang): string
    {
        $locales = config('app.locales', []);
        
        // Tjek om arrayet faktisk er et oversættelsesarray
        $keys = array_keys($translationArray);
        $isTranslationArray = (bool) count(array_intersect($keys, $locales));
        
        if (!$isTranslationArray) {
            // Hvis ikke, returner den første værdi eller en tom streng
            return is_string(reset($translationArray)) ? reset($translationArray) : '';
        }
        
        // Prøv i denne rækkefølge: 1) Ønsket sprog, 2) Standard sprog, 3) Engelsk, 4) Første tilgængelige
        if (isset($translationArray[$lang])) {
            return $translationArray[$lang];
        } elseif (isset($translationArray[$defaultLang])) {
            return $translationArray[$defaultLang];
        } elseif (isset($translationArray['en'])) {
            return $translationArray['en'];
        } else {
            // Returner den første værdi der er en streng
            foreach ($translationArray as $value) {
                if (is_string($value) && !empty($value)) {
                    return $value;
                }
            }
            return '';
        }
    }
}