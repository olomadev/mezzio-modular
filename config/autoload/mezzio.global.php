<?php

declare(strict_types=1);

return [
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
