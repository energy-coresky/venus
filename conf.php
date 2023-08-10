<?php

return [
    'view' => ['path' => __DIR__ . '/mvc'],
    'mem' => ['path' => __DIR__ . '/mvc'],
    'app' => [
        'type' => 'dev',
        'cli' => 'd:/web/hole',
        'require' => 'Parsedown SQLite3',
        'databases' => [
            'w' => ['driver' => 'sqlite3', 'dsn' => __DIR__ . '/venus.base'],
        ],
    ],
];
