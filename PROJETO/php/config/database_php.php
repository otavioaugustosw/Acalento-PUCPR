<?php
function connectDatabase(): mysqli
{
    return new mysqli(
        $_ENV['DATABASE_SERVER'],
        $_ENV['DATABASE_USER'],
        $_ENV['DATABASE_PASSWORD'],
        $_ENV['DATABASE_NAME']
    );
}