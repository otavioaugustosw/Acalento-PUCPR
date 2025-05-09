<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . "/models/admin_models_php.php");

if (isset($_GET['id'])) {
    // a função intval tranforma o valor em um valor inteiro, está segundo usado apenas por precaução
    $id = intval($_GET['id']);
    $conn = connectDatabase();

    $query = "UPDATE evento 
              SET inativo=1 
              WHERE id = $id";
    $resultado = $conn->query($query);

    if ($resultado) {
        header("Location: index.php?adm=14&success=4");
        exit;
    } else {
        header("Location: index.php?adm=13&error=6");
    }
}
?>