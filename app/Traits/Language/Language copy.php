<?php

namespace App\Traits\Language;

use Spatie\Translatable\HasTranslations as BaseHasTranslations; 
use Spatie\Translatable\Events\TranslationHasBeenSet;

trait Language {

    // Use the Spatie HasTranslations trait for basic translation functionality
    use BaseHasTranslations;

    // Variable to store language setting
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
        if(!$lang || $lang === "false" || $lang === "0" || $lang === "null") { 
            return $this; 
        }

        // Get available locales from the config
        $locales = config("app.locales");

        // If "default" is passed as the language, use the application's current locale
        if($lang == "default"){ 
            $lang = app()->getLocale(); 
        }

        // Translate the model's array attributes using the provided language
        return $this->translateArrays(parent::toArray(), $lang);
    }

    /**
     * Recursively translate an array of attributes based on the provided language.
     *
     * @param array $array The array to translate.
     * @param string $lang The language to translate to.
     * @return array The translated array.
     */
    public function translateArrays($array, string $lang) {
        // Get the application's default language
        $defaultLang = app()->getLocale();

        $translatedArray = [];

        // Loop through the array and translate each value if necessary
        foreach ($array as $key => $value) {

            // Special handling for "description" key (can be customized)
            $des = $key == "description";

            // Check if the value is an array (for nested translations)
            if (is_array($value)) {
                // If the desired language doesn't exist, use a fallback language
                if (!isset($value[$lang])) {
                    // Use default language as a fallback
                    if (isset($value[$defaultLang])) {
                        $lang = $defaultLang;
                    } 
                    // If default language isn't available, use "en" as a fallback
                    else if (isset($value["en"])) {
                        $lang = "en";
                    }
                }

                // If the language key exists in the array, use it
                if (array_key_exists($lang, $value)) {
                    $translatedArray[$key] = $value[$lang];
                } else {
                    // Otherwise, recursively translate the nested array
                    $translatedArray[$key] = $this->translateArrays($value, $lang);
                }
            } else {
                // For non-array values, just use the original value
                $translatedArray[$key] = $value;
            }
        }

        return $translatedArray;
    }
}
