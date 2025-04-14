<?php
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

include 'php/config/database_php.php';

$obj = conectDatabase();

$query = "SELECT * FROM usuario";

// Executa a query uma vez
$result = $obj->query($query);

// Verifica se a query foi bem-sucedida
if ($result) {
	while ($row = $result->fetch_assoc()) {
		echo $row['nome'] . "<br>";
	}
} else {
	echo "Erro na query: " . $obj->error;
}