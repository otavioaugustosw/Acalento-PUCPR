<?php
include (ROOT . "/php/config/database_php.php"); // Inclui a conexão se ainda não tiver

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Segurança básica
    $obj = connectDatabase();

    $query = "UPDATE evento 
              SET status=1 
              WHERE id = $id";
    $resultado = $obj->query($query);

    if ($resultado) {
        header("Location: index.php?adm=13&success=4");
        exit;
    } else {
        header("Location: index.php?adm=12&error=6");
    }
}
?>