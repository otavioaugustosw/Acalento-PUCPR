<?php

$isProduction = getenv('APP_ENV') === 'production';

if (!$isProduction) {
    require(__DIR__ . '/../../vendor/autoload.php');
    if (file_exists(__DIR__ . '/.env')) {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
    } else {
        die('.env não encontrado no ambiente de desenvolvimento.');
    }
}

