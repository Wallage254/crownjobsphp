<?php
// Database configuration
return [
    'host' => $_ENV['PGHOST'] ?? 'localhost',
    'port' => $_ENV['PGPORT'] ?? '5432',
    'dbname' => $_ENV['PGDATABASE'] ?? 'crownopportunities',
    'username' => $_ENV['PGUSER'] ?? 'postgres',
    'password' => $_ENV['PGPASSWORD'] ?? '',
    'dsn' => $_ENV['DATABASE_URL'] ?? null
];