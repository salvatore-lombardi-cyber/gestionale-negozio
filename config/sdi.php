<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Configurazione Sistema di Interscambio (SDI)
    |--------------------------------------------------------------------------
    |
    | Configurazioni per l'integrazione con l'Agenzia delle Entrate
    |
    */
    
    // Modalità test (true = locale, false = invio reale)
    'test_mode' => env('SDI_TEST_MODE', true),
    
    // Endpoint SDI Agenzia delle Entrate
    'endpoint' => env('SDI_ENDPOINT', 'https://ivaservizi.agenziaentrate.gov.it/ser/fatturapa/ricevi'),
    'query_endpoint' => env('SDI_QUERY_ENDPOINT', 'https://ivaservizi.agenziaentrate.gov.it/ser/fatturapa/ricevi'),
    
    // Test endpoint (per sviluppo)
    'test_endpoint' => env('SDI_TEST_ENDPOINT', 'https://testservizi.agenziaentrate.gov.it/ser/fatturapa/ricevi'),
    
    // Certificato digitale
    'certificate_path' => env('SDI_CERTIFICATE_PATH', storage_path('certificates/fatturazione.p12')),
    'certificate_password' => env('SDI_CERTIFICATE_PASSWORD'),
    
    // Configurazioni trasmissione
    'country_code' => 'IT',
    'transmission_format' => 'FPR12', // Formato per privati
    'document_type' => 'TD01', // Fattura standard
    
    // Timeout connessioni (secondi)
    'timeout' => env('SDI_TIMEOUT', 30),
    'connect_timeout' => env('SDI_CONNECT_TIMEOUT', 10),
    
    // Retry automatici
    'max_retries' => env('SDI_MAX_RETRIES', 3),
    'retry_delay' => env('SDI_RETRY_DELAY', 5), // secondi
    
    // Log e monitoraggio
    'log_requests' => env('SDI_LOG_REQUESTS', true),
    'log_responses' => env('SDI_LOG_RESPONSES', true),
    
    // Notifiche email per errori
    'notification_email' => env('SDI_NOTIFICATION_EMAIL'),
    
    // Codici di stato SDI
    'status_codes' => [
        'NS' => 'Notifica di scarto',
        'MC' => 'Mancata consegna',
        'RC' => 'Ricevuta di consegna',
        'NE' => 'Notifica di esito',
        'DT' => 'Decorrenza termini',
        'AT' => 'Attestazione di avvenuta trasmissione'
    ],
    
    // Formati file supportati
    'supported_formats' => [
        'xml' => 'Fattura non firmata',
        'xml.p7m' => 'Fattura firmata CAdES',
        'xml.p7s' => 'Fattura firmata CAdES detached'
    ]
];