<?php

declare(strict_types=1);

return [
    'data_manager' => [
        'common_schema_module' => 'Common',
    ],
    'authentication' => [
        'tablename' => 'users',
        'username' => 'email',
        'password' => 'password',
        'form' => [
            'username' => 'username',
            'password' => 'password',
        ]
    ],
    'translator' => [
        'locale' => [
            'tr', // default locale
            'en'  // fallback locale
        ],
        'translation_file_patterns' => [
            [
                'type' => 'PhpArray',
                'base_dir' => __DIR__ . '/../data/language',
                'pattern' => '%s/messages.php'
            ],
            [
                'type' => 'PhpArray',
                'base_dir' => __DIR__ . '/../data/language',
                'pattern' => '%s/labels.php',
                'text_domain' => 'labels',
            ],
            [
                'type' => 'PhpArray',
                'base_dir' => __DIR__ . '/../data/language',
                'pattern' => '%s/templates.php',
                'text_domain' => 'templates',
            ],
        ],
    ],
];
