<?php

return [
    'view' => ['path' => __DIR__ . '/mvc'],
    'mem' => ['path' => __DIR__ . '/mvc'],
    'app' => [
        'type' => 'dev',
        'require' => 'Parsedown SQLite3',
        'databases' => [
            '_w' => ['driver' => 'sqlite3', 'dsn' => __DIR__ . '/venus.base'],
        ],
    ],
];
