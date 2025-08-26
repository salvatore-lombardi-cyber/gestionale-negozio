<?php

/**
 * Configuration settings for the GROQ API integration.
 *
 * @return array
 *   An array of GROQ API configuration settings.
 *   - api_key: The API key to authenticate with the GROQ API.
 *   - api_base: The base URL for the GROQ API endpoint.
 *   - options: Additional options to pass to the GROQ API client.
 */
return [
    'api_key' => env('GROQ_API_KEY'),
    'model' => env('GROQ_MODEL', 'llama3-8b-8192'),
    'timeout' => env('GROQ_TIMEOUT', 30),
    'options' => [
        'temperature' => 0.7,
        'max_tokens' => 1000,
        'top_p' => 1.0,
        'frequency_penalty' => 0,
        'presence_penalty' => 0,
    ],
    'cache' => [
        'enabled' => true,
        'ttl' => 3600,
    ],
];
