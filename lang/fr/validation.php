<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Lignes de langue de validation
    |--------------------------------------------------------------------------
    |
    | Les lignes de langue suivantes contiennent les messages d'erreur par défaut utilisés
    | par la classe de validateur. Certaines de ces règles ont plusieurs versions
    | comme les règles de taille. N'hésitez pas à modifier chacun de ces messages ici.
    |
    */

    'accepted'             => 'Le champ :attribute doit être accepté.',
    'accepted_if'          => 'Le champ :attribute doit être accepté quand :other vaut :value.',
    'active_url'           => "Le champ :attribute n'est pas une URL valide.",
    'after'                => 'Le champ :attribute doit être une date postérieure au :date.',
    'after_or_equal'       => 'Le champ :attribute doit être une date postérieure ou égale au :date.',
    'alpha'                => 'Le champ :attribute doit seulement contenir des lettres.',
    'alpha_dash'           => 'Le champ :attribute doit seulement contenir des lettres, chiffres, tirets et underscores.',
    'alpha_num'            => 'Le champ :attribute doit seulement contenir des lettres et chiffres.',
    'array'                => 'Le champ :attribute doit être un tableau.',
    'ascii'                => 'Le champ :attribute ne peut contenir que des caractères alphanumériques simples et des symboles.',
    'before'               => 'Le champ :attribute doit être une date antérieure au :date.',
    'before_or_equal'      => 'Le champ :attribute doit être une date antérieure ou égale au :date.',
    'between'              => [
        'array'   => 'Le champ :attribute doit contenir entre :min et :max éléments.',
        'file'    => 'Le champ :attribute doit avoir une taille entre :min et :max kilo-octets.',
        'numeric' => 'Le champ :attribute doit être entre :min et :max.',
        'string'  => 'Le champ :attribute doit contenir entre :min et :max caractères.',
    ],
    'boolean'              => 'Le champ :attribute doit être vrai ou faux.',
    'confirmed'            => 'La confirmation du champ :attribute ne correspond pas.',
    'current_password'     => 'Le mot de passe est incorrect.',
    'date'                 => "Le champ :attribute n'est pas une date valide.",
    'date_equals'          => 'Le champ :attribute doit être une date égale à :date.',
    'date_format'          => 'Le champ :attribute ne correspond pas au format :format.',
    'decimal'              => 'Le champ :attribute doit avoir :decimal décimales.',
    'declined'             => 'Le champ :attribute doit être refusé.',
    'declined_if'          => 'Le champ :attribute doit être refusé quand :other vaut :value.',
    'different'            => 'Les champs :attribute et :other doivent être différents.',
    'digits'               => 'Le champ :attribute doit avoir :digits chiffres.',
    'digits_between'       => 'Le champ :attribute doit avoir entre :min et :max chiffres.',
    'dimensions'           => "Le champ :attribute a des dimensions d'image invalides.",
    'distinct'             => 'Le champ :attribute a une valeur en double.',
    'doesnt_end_with'      => 'Le champ :attribute ne doit pas finir par :values.',
    'doesnt_start_with'    => 'Le champ :attribute ne doit pas commencer par :values.',
    'email'                => 'Le champ :attribute doit être une adresse email valide.',
    'ends_with'            => 'Le champ :attribute doit finir par l’une des valeurs suivantes : :values.',
    'enum'                 => 'Le champ sélectionné :attribute est invalide.',
    'exists'               => 'Le champ sélectionné :attribute est invalide.',
    'file'                 => 'Le champ :attribute doit être un fichier.',
    'filled'               => 'Le champ :attribute doit avoir une valeur.',
    'gt'                   => [
        'array'   => 'Le champ :attribute doit contenir plus de :value éléments.',
        'file'    => 'Le champ :attribute doit être supérieur à :value kilo-octets.',
        'numeric' => 'Le champ :attribute doit être supérieur à :value.',
        'string'  => 'Le champ :attribute doit contenir plus de :value caractères.',
    ],
    'gte'                  => [
        'array'   => 'Le champ :attribute doit contenir au moins :value éléments.',
        'file'    => 'Le champ :attribute doit être supérieur ou égal à :value kilo-octets.',
        'numeric' => 'Le champ :attribute doit être supérieur ou égal à :value.',
        'string'  => 'Le champ :attribute doit contenir au moins :value caractères.',
    ],
    'image'                => 'Le champ :attribute doit être une image.',
    'in'                   => 'Le champ sélectionné :attribute est invalide.',
    'in_array'             => 'Le champ :attribute n’existe pas dans :other.',
    'integer'              => 'Le champ :attribute doit être un entier.',
    'ip'                   => 'Le champ :attribute doit être une adresse IP valide.',
    'ipv4'                 => 'Le champ :attribute doit être une adresse IPv4 valide.',
    'ipv6'                 => 'Le champ :attribute doit être une adresse IPv6 valide.',
    'json'                 => 'Le champ :attribute doit être une chaîne JSON valide.',
    'lowercase'            => 'Le champ :attribute doit être en minuscules.',
    'lt'                   => [
        'array'   => 'Le champ :attribute doit contenir moins de :value éléments.',
        'file'    => 'Le champ :attribute doit être inférieur à :value kilo-octets.',
        'numeric' => 'Le champ :attribute doit être inférieur à :value.',
        'string'  => 'Le champ :attribute doit contenir moins de :value caractères.',
    ],
    'lte'                  => [
        'array'   => 'Le champ :attribute ne doit pas contenir plus de :value éléments.',
        'file'    => 'Le champ :attribute doit être inférieur ou égal à :value kilo-octets.',
        'numeric' => 'Le champ :attribute doit être inférieur ou égal à :value.',
        'string'  => 'Le champ :attribute doit contenir au maximum :value caractères.',
    ],
    'mac_address'          => 'Le champ :attribute doit être une adresse MAC valide.',
    'max'                  => [
        'array'   => 'Le champ :attribute ne peut contenir plus de :max éléments.',
        'file'    => 'Le champ :attribute ne peut pas dépasser :max kilo-octets.',
        'numeric' => 'Le champ :attribute ne peut pas être supérieur à :max.',
        'string'  => 'Le champ :attribute ne peut pas contenir plus de :max caractères.',
    ],
    'max_digits'           => 'Le champ :attribute ne peut pas contenir plus de :max chiffres.',
    'mimes'                => 'Le champ :attribute doit être un fichier de type : :values.',
    'mimetypes'            => 'Le champ :attribute doit être un fichier de type : :values.',
    'min'                  => [
        'array'   => 'Le champ :attribute doit contenir au moins :min éléments.',
        'file'    => 'Le champ :attribute doit avoir au moins :min kilo-octets.',
        'numeric' => 'Le champ :attribute doit être au moins égal à :min.',
        'string'  => 'Le champ :attribute doit contenir au moins :min caractères.',
    ],
    'min_digits'           => 'Le champ :attribute doit contenir au moins :min chiffres.',
    'missing'              => 'Le champ :attribute doit être manquant.',
    'missing_if'           => 'Le champ :attribute doit être manquant quand :other vaut :value.',
    'missing_unless'       => 'Le champ :attribute doit être manquant sauf si :other vaut :value.',
    'missing_with'         => 'Le champ :attribute doit être manquant quand :values est présent.',
    'missing_with_all'     => 'Le champ :attribute doit être manquant quand :values sont présents.',
    'multiple_of'          => 'Le champ :attribute doit être un multiple de :value.',
    'not_in'               => 'Le champ sélectionné :attribute est invalide.',
    'not_regex'            => "Le format du champ :attribute est invalide.",
    'numeric'              => 'Le champ :attribute doit être un nombre.',
    'password'             => [
        'letters'       => 'Le champ :attribute doit contenir au moins une lettre.',
        'mixed'         => 'Le champ :attribute doit contenir au moins une majuscule et une minuscule.',
        'numbers'       => 'Le champ :attribute doit contenir au moins un chiffre.',
        'symbols'       => 'Le champ :attribute doit contenir au moins un symbole.',
        'uncompromised' => 'Le champ :attribute est apparu dans une fuite de données. Veuillez choisir un autre mot de passe.',
    ],
    'present'              => 'Le champ :attribute doit être présent.',
    'prohibited'           => 'Le champ :attribute est interdit.',
    'prohibited_if'        => 'Le champ :attribute est interdit quand :other vaut :value.',
    'prohibited_unless'    => 'Le champ :attribute est interdit sauf si :other est dans :values.',
    'prohibits'            => 'Le champ :attribute interdit la présence de :other.',
    'regex'                => "Le format du champ :attribute est invalide.",
    'required'             => 'Le champ :attribute est obligatoire.',
    'required_array_keys'  => 'Le champ :attribute doit contenir des entrées pour : :values.',
    'required_if'          => 'Le champ :attribute est obligatoire quand :other vaut :value.',
    'required_if_accepted' => 'Le champ :attribute est obligatoire quand :other est accepté.',
    'required_unless'      => 'Le champ :attribute est obligatoire sauf si :other est dans :values.',
    'required_with'        => 'Le champ :attribute est obligatoire quand :values est présent.',
    'required_with_all'    => 'Le champ :attribute est obligatoire quand :values sont présents.',
    'required_without'     => 'Le champ :attribute est obligatoire quand :values n’est pas présent.',
    'required_without_all' => 'Le champ :attribute est obligatoire quand aucun de :values n’est présent.',
    'same'                 => 'Les champs :attribute et :other doivent correspondre.',
    'size'                 => [
        'array'   => 'Le champ :attribute doit contenir :size éléments.',
        'file'    => 'Le champ :attribute doit avoir une taille de :size kilo-octets.',
        'numeric' => 'Le champ :attribute doit être :size.',
        'string'  => 'Le champ :attribute doit contenir :size caractères.',
    ],
    'starts_with'          => 'Le champ :attribute doit commencer par l’un des éléments suivants : :values.',
    'string'               => 'Le champ :attribute doit être une chaîne de caractères.',
    'timezone'             => 'Le champ :attribute doit être un fuseau horaire valide.',
    'unique'               => 'Le champ :attribute a déjà été pris.',
    'uploaded'             => 'Le champ :attribute n’a pas pu être téléversé.',
    'uppercase'            => 'Le champ :attribute doit être en majuscules.',
    'url'                  => 'Le champ :attribute doit être une URL valide.',
    'ulid'                 => 'Le champ :attribute doit être un ULID valide.',
    'uuid'                 => 'Le champ :attribute doit être un UUID valide.',

    /*
    |--------------------------------------------------------------------------
    | Lignes de langue personnalisées pour la validation
    |--------------------------------------------------------------------------
    |
    | Ici, vous pouvez spécifier des messages de validation personnalisés pour les attributs en utilisant
    | la convention "attribute.rule" pour nommer les lignes. Cela rend rapide la
    | spécification d'une ligne de langage personnalisée spécifique à une règle d'attribut donnée.
    |
    */

    'custom' => [
        'email' => [
            'unique' => "Cet email est déjà utilisé.",
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Attributs de validation personnalisés
    |--------------------------------------------------------------------------
    |
    | Les lignes suivantes sont utilisées pour échanger notre placeholder d'attribut
    | par quelque chose de plus convivial pour le lecteur comme "Adresse e-mail"
    | au lieu de "email". Cela nous aide à rendre nos messages plus expressifs.
    |
    */

    'attributes' => [],

];
