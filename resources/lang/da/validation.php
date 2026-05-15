<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Følgende sproglinjer indeholder standardfejlmeddelelser, som bruges af
    | valideringsklassen. Nogle af disse regler har flere versioner, f.eks.
    | størrelsesreglerne. Du er velkommen til at tilpasse hver af disse meddelelser her.
    |
    */

    'accepted' => ':attribute skal accepteres.',
    'active_url' => ':attribute er ikke en gyldig URL.',
    'after' => ':attribute skal være en dato efter :date.',
    'after_or_equal' => ':attribute skal være en dato efter eller lig med :date.',
    'alpha' => ':attribute må kun indeholde bogstaver.',
    'alpha_dash' => ':attribute må kun indeholde bogstaver, tal, bindestreger og understregninger.',
    'alpha_num' => ':attribute må kun indeholde bogstaver og tal.',
    'array' => ':attribute skal være et array.',
    'before' => ':attribute skal være en dato før :date.',
    'before_or_equal' => ':attribute skal være en dato før eller lig med :date.',
    'between' => [
        'numeric' => ':attribute skal være mellem :min og :max.',
        'file' => ':attribute skal være mellem :min og :max kilobytes.',
        'string' => ':attribute skal være mellem :min og :max tegn.',
        'array' => ':attribute skal have mellem :min og :max elementer.',
    ],
    'boolean' => ':attribute feltet skal være sandt eller falsk.',
    'confirmed' => ':attribute bekræftelse matcher ikke.',
    'date' => ':attribute er ikke en gyldig dato.',
    'date_equals' => ':attribute skal være en dato lig med :date.',
    'date_format' => ':attribute matcher ikke formatet :format.',
    'different' => ':attribute og :other skal være forskellige.',
    'digits' => ':attribute skal være :digits cifre.',
    'digits_between' => ':attribute skal være mellem :min og :max cifre.',
    'dimensions' => ':attribute har ugyldige billeddimensioner.',
    'distinct' => ':attribute feltet har en duplikatværdi.',
    'email' => ':attribute skal være en gyldig emailadresse.',
    'ends_with' => ':attribute skal ende med en af følgende: :values.',
    'exists' => 'Det valgte :attribute er ugyldigt.',
    'file' => ':attribute skal være en fil.',
    'filled' => ':attribute feltet skal have en værdi.',
    'gt' => [
        'numeric' => ':attribute skal være større end :value.',
        'file' => ':attribute skal være større end :value kilobytes.',
        'string' => ':attribute skal være længere end :value tegn.',
        'array' => ':attribute skal have flere end :value elementer.',
    ],
    'gte' => [
        'numeric' => ':attribute skal være større end eller lig med :value.',
        'file' => ':attribute skal være større end eller lig med :value kilobytes.',
        'string' => ':attribute skal være længere end eller lig med :value tegn.',
        'array' => ':attribute skal have :value elementer eller flere.',
    ],
    'image' => ':attribute skal være et billede.',
    'in' => 'Det valgte :attribute er ugyldigt.',
    'in_array' => ':attribute feltet findes ikke i :other.',
    'integer' => ':attribute skal være et heltal.',
    'ip' => ':attribute skal være en gyldig IP adresse.',
    'ipv4' => ':attribute skal være en gyldig IPv4 adresse.',
    'ipv6' => ':attribute skal være en gyldig IPv6 adresse.',
    'json' => ':attribute skal være en gyldig JSON streng.',
    'lt' => [
        'numeric' => ':attribute skal være mindre end :value.',
        'file' => ':attribute skal være mindre end :value kilobytes.',
        'string' => ':attribute skal være kortere end :value tegn.',
        'array' => ':attribute skal have færre end :value elementer.',
    ],
    'lte' => [
        'numeric' => ':attribute skal være mindre end eller lig med :value.',
        'file' => ':attribute skal være mindre end eller lig med :value kilobytes.',
        'string' => ':attribute skal være kortere end eller lig med :value tegn.',
        'array' => ':attribute må ikke have mere end :value elementer.',
    ],
    'max' => [
        'numeric' => ':attribute må ikke være større end :max.',
        'file' => ':attribute må ikke være større end :max kilobytes.',
        'string' => ':attribute må ikke være længere end :max tegn.',
        'array' => ':attribute må ikke have mere end :max elementer.',
    ],
    'mimes' => ':attribute skal være en fil af typen: :values.',
    'mimetypes' => ':attribute skal være en fil af typen: :values.',
    'min' => [
        'numeric' => ':attribute skal være mindst :min.',
        'file' => ':attribute skal være mindst :min kilobytes.',
        'string' => ':attribute skal være mindst :min tegn.',
        'array' => ':attribute skal have mindst :min elementer.',
    ],
    'multiple_of' => ':attribute skal være et multiplum af :value.',
    'not_in' => 'Det valgte :attribute er ugyldigt.',
    'not_regex' => ':attribute formatet er ugyldigt.',
    'numeric' => ':attribute skal være et tal.',
    'password' => 'Kodeordet er forkert.',
    'present' => ':attribute feltet skal være til stede.',
    'regex' => ':attribute formatet er ugyldigt.',
    'required' => ':attribute feltet er påkrævet.',
    'required_if' => ':attribute feltet er påkrævet, når :other er :value.',
    'required_unless' => ':attribute feltet er påkrævet, medmindre :other er i :values.',
    'required_with' => ':attribute feltet er påkrævet.',
    'required_with_all' => ':attribute feltet er påkrævet.',
    'required_without' => ':attribute feltet er påkrævet, når :values ikke er til stede.',
    'required_without_all' => ':attribute feltet er påkrævet, når ingen af :values er til stede.',
    'same' => ':attribute og :other skal matche.',
    'size' => [
        'numeric' => ':attribute skal være :size.',
        'file' => ':attribute skal være :size kilobytes.',
        'string' => ':attribute skal være :size tegn.',
        'array' => ':attribute skal indeholde :size elementer.',
    ],
    'starts_with' => ':attribute skal starte med en af følgende: :values.',
    'string' => ':attribute skal være en streng.',
    'timezone' => ':attribute skal være en gyldig tidszone.',
    'unique' => ':attribute er allerede taget.',
    'uploaded' => ':attribute kunne ikke uploades.',
    'url' => ':attribute formatet er ugyldigt.',
    'uuid' => ':attribute skal være en gyldig UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Her kan du specificere tilpassede valideringsmeddelelser for attributter ved hjælp af
    | konventionen "attribut.rule" for at navngive linjerne. Dette gør det hurtigt at
    | specificere en bestemt tilpasset sproglinje for en given attributregel.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'tilpasset-meddelelse',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | Følgende sproglinjer bruges til at udskifte vores attributpladsholder
    | med noget mere læsevenligt, såsom "E-mail adresse" i stedet
    | for "email". Dette hjælper os simpelthen med at gøre vores meddelelser mere udtryksfulde.
    |
    */

    'attributes' => [
        
    ],



];
