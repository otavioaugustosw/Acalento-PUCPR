<?php
function hasColumn(mysqli $bd, string $tabela, string $coluna): bool
{
    $query = "SHOW COLUMNS FROM $tabela LIKE '$coluna'";
    $resultado = $bd->query($query);
    if (!$resultado) {
        return false;
    }
    $existe = $resultado->num_rows > 0;

    $resultado->free();   // boa prática: libera resultado da memória
    return $existe;
}
