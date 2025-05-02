<?php
function connectDatabase(): mysqli
{
    $isProduction = getenv('APP_ENV') === 'production';
    if (!$isProduction) {
        return new mysqli(
            $_ENV['DATABASE_SERVER'],
            $_ENV['DATABASE_USER'],
            $_ENV['DATABASE_PASSWORD'],
            $_ENV['DATABASE_NAME'],
            $_ENV['DATABASE_PORT']
        );
    }
    return new mysqli(
        getenv('DATABASE_SERVER'),
        getenv('DATABASE_USER'),
        getenv('DATABASE_PASSWORD'),
        getenv('DATABASE_NAME'),
        getenv('DATABASE_PORT')
    );
}

