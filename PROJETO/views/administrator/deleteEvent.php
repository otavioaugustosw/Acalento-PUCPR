<?php
include (ROOT . "/php/config/database_php.php"); // Inclui a conexão se ainda não tiver

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Segurança básica
    $obj = connectDatabase();

    $query = "DELETE FROM evento WHERE id = $id";
    $resultado = $obj->query($query);

    if ($resultado) {
        header("Location: index.php?adm=5&success=4");
        exit;
    } else {
        showError(6);
    }
}
?>