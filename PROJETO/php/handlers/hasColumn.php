<?php
/**
 * @param mysqli $bd recebe a conexão com o banco de dados
 * @param string $tabela recebe o nome da tabela
 * @param string $coluna recebe a coluna quer você quer saber se existe ou não
 * @return bool
 */
function hasColumn(mysqli $bd, string $tabela, string $coluna): bool
{

    $query = "SHOW COLUMNS FROM $tabela LIKE '$coluna'";
    $resultado = $bd->query($query);
    if (!$resultado) {
        return false;
    }
    $existe = $resultado->num_rows > 0;

    $resultado->free();
    return $existe;
}
