<?php
function connectDatabase()
{
    try {
        return new mysqli(
            $_ENV['DATABASE_SERVER'],
            $_ENV['DATABASE_USER'],
            $_ENV['DATABASE_PASSWORD'],
            $_ENV['DATABASE_NAME'],
            $_ENV['DATABASE_PORT']
        );
    } catch (mysqli_sql_exception $E) {
        header('Location: index.php');
}
}