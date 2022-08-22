<?php

return
[
    'paths' => [
        'migrations' => __DIR__.'/phinx/migrations',
        'seeds' => __DIR__.'/phinx/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'development' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'test_cesu_alus',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8mb4',
        ],
    ],
    'version_order' => 'creation'
];
