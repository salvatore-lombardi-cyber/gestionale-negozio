<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Il campo :attribute deve essere accettato.',
    'active_url' => 'Il campo :attribute non è un URL valido.',
    'after' => 'Il campo :attribute deve essere una data successiva al :date.',
    'after_or_equal' => 'Il campo :attribute deve essere una data successiva o uguale al :date.',
    'alpha' => 'Il campo :attribute può contenere solo lettere.',
    'alpha_dash' => 'Il campo :attribute può contenere solo lettere, numeri, trattini e trattini bassi.',
    'alpha_num' => 'Il campo :attribute può contenere solo lettere e numeri.',
    'array' => 'Il campo :attribute deve essere un array.',
    'before' => 'Il campo :attribute deve essere una data precedente al :date.',
    'before_or_equal' => 'Il campo :attribute deve essere una data precedente o uguale al :date.',
    'between' => [
        'numeric' => 'Il campo :attribute deve essere compreso tra :min e :max.',
        'file' => 'Il campo :attribute deve essere compreso tra :min e :max kilobyte.',
        'string' => 'Il campo :attribute deve essere compreso tra :min e :max caratteri.',
        'array' => 'Il campo :attribute deve avere tra :min e :max elementi.',
    ],
    'boolean' => 'Il campo :attribute deve essere vero o falso.',
    'confirmed' => 'La conferma del campo :attribute non corrisponde.',
    'date' => 'Il campo :attribute non è una data valida.',
    'date_equals' => 'Il campo :attribute deve essere una data uguale a :date.',
    'date_format' => 'Il campo :attribute non corrisponde al formato :format.',
    'different' => 'Il campo :attribute e :other devono essere diversi.',
    'digits' => 'Il campo :attribute deve essere di :digits cifre.',
    'digits_between' => 'Il campo :attribute deve essere compreso tra :min e :max cifre.',
    'dimensions' => 'Il campo :attribute ha dimensioni di immagine non valide.',
    'distinct' => 'Il campo :attribute ha un valore duplicato.',
    'email' => 'Il campo :attribute deve essere un indirizzo email valido.',
    'ends_with' => 'Il campo :attribute deve terminare con uno dei seguenti valori: :values.',
    'exists' => 'Il valore selezionato per :attribute non è valido.',
    'file' => 'Il campo :attribute deve essere un file.',
    'filled' => 'Il campo :attribute deve avere un valore.',
    'gt' => [
        'numeric' => 'Il campo :attribute deve essere maggiore di :value.',
        'file' => 'Il campo :attribute deve essere maggiore di :value kilobyte.',
        'string' => 'Il campo :attribute deve essere maggiore di :value caratteri.',
        'array' => 'Il campo :attribute deve avere più di :value elementi.',
    ],
    'gte' => [
        'numeric' => 'Il campo :attribute deve essere maggiore o uguale a :value.',
        'file' => 'Il campo :attribute deve essere maggiore o uguale a :value kilobyte.',
        'string' => 'Il campo :attribute deve essere maggiore o uguale a :value caratteri.',
        'array' => 'Il campo :attribute deve avere :value elementi o più.',
    ],
    'image' => 'Il campo :attribute deve essere un\'immagine.',
    'in' => 'Il valore selezionato per :attribute non è valido.',
    'in_array' => 'Il campo :attribute non esiste in :other.',
    'integer' => 'Il campo :attribute deve essere un numero intero.',
    'ip' => 'Il campo :attribute deve essere un indirizzo IP valido.',
    'ipv4' => 'Il campo :attribute deve essere un indirizzo IPv4 valido.',
    'ipv6' => 'Il campo :attribute deve essere un indirizzo IPv6 valido.',
    'json' => 'Il campo :attribute deve essere una stringa JSON valida.',
    'lt' => [
        'numeric' => 'Il campo :attribute deve essere minore di :value.',
        'file' => 'Il campo :attribute deve essere minore di :value kilobyte.',
        'string' => 'Il campo :attribute deve essere minore di :value caratteri.',
        'array' => 'Il campo :attribute deve avere meno di :value elementi.',
    ],
    'lte' => [
        'numeric' => 'Il campo :attribute deve essere minore o uguale a :value.',
        'file' => 'Il campo :attribute deve essere minore o uguale a :value kilobyte.',
        'string' => 'Il campo :attribute deve essere minore o uguale a :value caratteri.',
        'array' => 'Il campo :attribute non deve avere più di :value elementi.',
    ],
    'max' => [
        'numeric' => 'Il campo :attribute non può essere maggiore di :max.',
        'file' => 'Il campo :attribute non può essere maggiore di :max kilobyte.',
        'string' => 'Il campo :attribute non può essere maggiore di :max caratteri.',
        'array' => 'Il campo :attribute non può avere più di :max elementi.',
    ],
    'mimes' => 'Il campo :attribute deve essere un file di tipo: :values.',
    'mimetypes' => 'Il campo :attribute deve essere un file di tipo: :values.',
    'min' => [
        'numeric' => 'Il campo :attribute deve essere almeno :min.',
        'file' => 'Il campo :attribute deve essere almeno di :min kilobyte.',
        'string' => 'Il campo :attribute deve contenere almeno :min caratteri.',
        'array' => 'Il campo :attribute deve avere almeno :min elementi.',
    ],
    'not_in' => 'Il valore selezionato per :attribute non è valido.',
    'not_regex' => 'Il formato del campo :attribute non è valido.',
    'numeric' => 'Il campo :attribute deve essere un numero.',
    'password' => 'La password non è corretta.',
    'present' => 'Il campo :attribute deve essere presente.',
    'regex' => 'Il formato del campo :attribute non è valido.',
    'required' => 'Il campo :attribute è obbligatorio.',
    'required_if' => 'Il campo :attribute è obbligatorio quando :other è :value.',
    'required_unless' => 'Il campo :attribute è obbligatorio a meno che :other sia in :values.',
    'required_with' => 'Il campo :attribute è obbligatorio quando :values è presente.',
    'required_with_all' => 'Il campo :attribute è obbligatorio quando :values sono presenti.',
    'required_without' => 'Il campo :attribute è obbligatorio quando :values non è presente.',
    'required_without_all' => 'Il campo :attribute è obbligatorio quando nessuno dei :values è presente.',
    'same' => 'Il campo :attribute e :other devono corrispondere.',
    'size' => [
        'numeric' => 'Il campo :attribute deve essere :size.',
        'file' => 'Il campo :attribute deve essere di :size kilobyte.',
        'string' => 'Il campo :attribute deve essere di :size caratteri.',
        'array' => 'Il campo :attribute deve contenere :size elementi.',
    ],
    'starts_with' => 'Il campo :attribute deve iniziare con uno dei seguenti valori: :values.',
    'string' => 'Il campo :attribute deve essere una stringa.',
    'timezone' => 'Il campo :attribute deve essere un fuso orario valido.',
    'unique' => 'Il valore del campo :attribute è già stato utilizzato.',
    'uploaded' => 'Il campo :attribute non è stato caricato.',
    'url' => 'Il formato del campo :attribute non è valido.',
    'uuid' => 'Il campo :attribute deve essere un UUID valido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "rule.attribute" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'description' => 'descrizione',
        'name' => 'nome',
        'code' => 'codice',
        'email' => 'email',
        'password' => 'password',
        'password_confirmation' => 'conferma password',
        'nome_associazione' => 'nome associazione',
        'tax_rate_id' => 'aliquota IVA',
        'vat_nature_id' => 'natura IVA',
        'percentuale' => 'percentuale',
        'vat_code' => 'codice IVA',
        'nature' => 'natura',
        'legal_reference' => 'riferimento normativo',
        'address' => 'indirizzo',
        'phone' => 'telefono',
        'comment' => 'commento',
    ],

];