<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurazione Gestione Tabelle Enterprise
    |--------------------------------------------------------------------------
    |
    | Configurazioni per il sistema di gestione tabelle di configurazione
    | Architettura enterprise con sicurezza e performance ottimizzate
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'enabled' => env('GESTIONE_TABELLE_CACHE_ENABLED', true),
        'default_ttl' => env('GESTIONE_TABELLE_CACHE_TTL', 3600),
        'stats_ttl' => env('GESTIONE_TABELLE_STATS_TTL', 1800),
        'config_ttl' => env('GESTIONE_TABELLE_CONFIG_TTL', 7200),
        'prefix' => env('GESTIONE_TABELLE_CACHE_PREFIX', 'gestione_tabelle'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    */
    'security' => [
        'rate_limiting' => [
            'enabled' => env('GESTIONE_TABELLE_RATE_LIMITING', true),
            'max_attempts_per_operation' => env('GESTIONE_TABELLE_MAX_ATTEMPTS_OP', 60),
            'max_attempts_per_user' => env('GESTIONE_TABELLE_MAX_ATTEMPTS_USER', 1000),
            'timeout_minutes' => env('GESTIONE_TABELLE_TIMEOUT', 60),
        ],
        
        'ip_restrictions' => [
            'whitelist_enabled' => env('GESTIONE_TABELLE_IP_WHITELIST_ENABLED', false),
            'blacklist_enabled' => env('GESTIONE_TABELLE_IP_BLACKLIST_ENABLED', true),
        ],
        
        'time_restrictions' => [
            'critical_operations_hours' => [
                'start' => env('GESTIONE_TABELLE_CRITICAL_START_HOUR', 8),
                'end' => env('GESTIONE_TABELLE_CRITICAL_END_HOUR', 20),
            ],
            'weekend_deletions' => env('GESTIONE_TABELLE_WEEKEND_DELETIONS', false),
        ],
        
        'audit_logging' => [
            'enabled' => env('GESTIONE_TABELLE_AUDIT_ENABLED', true),
            'log_file' => env('GESTIONE_TABELLE_AUDIT_FILE', 'audit_gestione_tabelle.log'),
            'retention_days' => env('GESTIONE_TABELLE_AUDIT_RETENTION', 365),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IP Whitelist/Blacklist
    |--------------------------------------------------------------------------
    */
    'ip_whitelist' => [
        // Aggiungi IP autorizzati per operazioni critiche
        // '192.168.1.100',
        // '10.0.0.50',
    ],

    'ip_blacklist' => [
        // Aggiungi IP bloccati
        // '192.168.1.200',
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Configuration
    |--------------------------------------------------------------------------
    */
    'performance' => [
        'pagination' => [
            'default_per_page' => env('GESTIONE_TABELLE_PAGINATION', 20),
            'max_per_page' => env('GESTIONE_TABELLE_MAX_PAGINATION', 100),
        ],
        
        'bulk_operations' => [
            'max_items' => env('GESTIONE_TABELLE_BULK_MAX', 1000),
            'chunk_size' => env('GESTIONE_TABELLE_BULK_CHUNK', 100),
        ],
        
        'export' => [
            'max_records' => env('GESTIONE_TABELLE_EXPORT_MAX', 10000),
            'timeout_seconds' => env('GESTIONE_TABELLE_EXPORT_TIMEOUT', 300),
            'memory_limit' => env('GESTIONE_TABELLE_EXPORT_MEMORY', '512M'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation Configuration
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'strict_mode' => env('GESTIONE_TABELLE_STRICT_VALIDATION', true),
        'sanitization' => [
            'xss_protection' => env('GESTIONE_TABELLE_XSS_PROTECTION', true),
            'sql_injection_protection' => env('GESTIONE_TABELLE_SQL_PROTECTION', true),
            'max_field_length' => env('GESTIONE_TABELLE_MAX_FIELD_LENGTH', 10000),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tables Configuration
    |--------------------------------------------------------------------------
    |
    | Configurazione per ogni tabella gestita dal sistema
    |
    */
    'tables' => [
        'banche' => [
            'name' => 'Banche',
            'icon' => 'bi-bank',
            'color' => 'primary',
            'description' => 'Gestione coordinate bancarie e istituti di credito',
            'critical' => true,
            'cache_ttl' => 7200,
            'permissions' => [
                'view' => 'view_banks',
                'create' => 'create_banks',
                'edit' => 'edit_banks',
                'delete' => 'delete_banks',
                'export' => 'export_banks',
            ],
        ],

        'clienti' => [
            'name' => 'Clienti',
            'icon' => 'bi-people',
            'color' => 'success',
            'description' => 'Gestione anagrafica clienti',
            'critical' => true,
            'cache_ttl' => 3600,
            'permissions' => [
                'view' => 'view_clients',
                'create' => 'create_clients',
                'edit' => 'edit_clients',
                'delete' => 'delete_clients',
                'export' => 'export_clients',
            ],
        ],

        'fornitori' => [
            'name' => 'Fornitori',
            'icon' => 'bi-truck',
            'color' => 'warning',
            'description' => 'Gestione anagrafica fornitori',
            'critical' => true,
            'cache_ttl' => 3600,
            'permissions' => [
                'view' => 'view_suppliers',
                'create' => 'create_suppliers',
                'edit' => 'edit_suppliers',
                'delete' => 'delete_suppliers',
                'export' => 'export_suppliers',
            ],
        ],

        'categorie_prodotti' => [
            'name' => 'Categorie Prodotti',
            'icon' => 'bi-tags',
            'color' => 'info',
            'description' => 'Gestione categorie merceologiche',
            'critical' => true,
            'cache_ttl' => 7200,
            'permissions' => [
                'view' => 'view_categories',
                'create' => 'create_categories',
                'edit' => 'edit_categories',
                'delete' => 'delete_categories',
                'export' => 'export_categories',
            ],
        ],

        'unita_misura' => [
            'name' => 'Unità di Misura',
            'icon' => 'bi-rulers',
            'color' => 'secondary',
            'description' => 'Gestione unità di misura',
            'critical' => true,
            'cache_ttl' => 7200,
            'permissions' => [
                'view' => 'view_units',
                'create' => 'create_units',
                'edit' => 'edit_units',
                'delete' => 'delete_units',
                'export' => 'export_units',
            ],
        ],

        'nature_iva' => [
            'name' => 'Nature IVA',
            'icon' => 'bi-percent',
            'color' => 'danger',
            'description' => 'Gestione aliquote e nature IVA',
            'critical' => true,
            'cache_ttl' => 7200,
            'permissions' => [
                'view' => 'view_vat_rates',
                'create' => 'create_vat_rates',
                'edit' => 'edit_vat_rates',
                'delete' => 'delete_vat_rates',
                'export' => 'export_vat_rates',
            ],
        ],

        'modalita_pagamento' => [
            'name' => 'Modalità Pagamento',
            'icon' => 'bi-credit-card',
            'color' => 'primary',
            'description' => 'Gestione modalità e termini di pagamento',
            'critical' => true,
            'cache_ttl' => 3600,
            'permissions' => [
                'view' => 'view_payment_methods',
                'create' => 'create_payment_methods',
                'edit' => 'edit_payment_methods',
                'delete' => 'delete_payment_methods',
                'export' => 'export_payment_methods',
            ],
        ],

        'vettori' => [
            'name' => 'Vettori',
            'icon' => 'bi-truck-flatbed',
            'color' => 'warning',
            'description' => 'Gestione vettori e trasportatori',
            'critical' => false,
            'cache_ttl' => 3600,
            'permissions' => [
                'view' => 'view_carriers',
                'create' => 'create_carriers',
                'edit' => 'edit_carriers',
                'delete' => 'delete_carriers',
                'export' => 'export_carriers',
            ],
        ],

        // TODO: Aggiungere configurazioni per le restanti tabelle...
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications Configuration
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'critical_operations' => [
            'enabled' => env('GESTIONE_TABELLE_NOTIFICATIONS', true),
            'channels' => ['slack', 'email'],
            'operations' => ['eliminazione', 'modifica_bulk', 'export_completo'],
        ],
        
        'security_alerts' => [
            'enabled' => env('GESTIONE_TABELLE_SECURITY_ALERTS', true),
            'channels' => ['slack', 'email'],
            'events' => ['rate_limit_exceeded', 'sql_injection_attempt', 'xss_attempt'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Monitoring Configuration
    |--------------------------------------------------------------------------
    */
    'monitoring' => [
        'performance_tracking' => env('GESTIONE_TABELLE_PERFORMANCE_TRACKING', true),
        'slow_query_threshold' => env('GESTIONE_TABELLE_SLOW_QUERY_MS', 1000),
        'memory_usage_alerts' => env('GESTIONE_TABELLE_MEMORY_ALERTS', true),
        'cache_hit_rate_threshold' => env('GESTIONE_TABELLE_CACHE_THRESHOLD', 0.8),
    ],

    /*
    |--------------------------------------------------------------------------
    | Backup Configuration
    |--------------------------------------------------------------------------
    */
    'backup' => [
        'enabled' => env('GESTIONE_TABELLE_BACKUP_ENABLED', true),
        'automatic_before_critical' => env('GESTIONE_TABELLE_AUTO_BACKUP', true),
        'retention_days' => env('GESTIONE_TABELLE_BACKUP_RETENTION', 30),
        'storage_disk' => env('GESTIONE_TABELLE_BACKUP_DISK', 'local'),
    ],
];