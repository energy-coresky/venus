<?php

$plans = [
    'view' => ['path' => __DIR__ . '/w3'],
    'glob' => ['path' => __DIR__ . '/w3'],
    'app' => ['type' => 'dev'],
];

SKY::$databases += [
    '_w' => ['driver' => 'sqlite3', 'dsn' => __DIR__ . '/venus.base'],
];
