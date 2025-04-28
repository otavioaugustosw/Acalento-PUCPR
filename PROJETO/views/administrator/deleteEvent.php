<?php
include (ROOT . "/php/config/database_php.php");

if (isset($_GET['id'])) {
    // a função intval tranforma o valor em um valor inteiro, está segundo usado apenas por precaução
    $id = intval($_GET['id']);
    $obj = connectDatabase();

    $query = "UPDATE evento 
              SET status=1 
              WHERE id = $id";
    $resultado = $obj->query($query);

    if ($resultado) {
        header("Location: index.php?adm=14&success=4");
        exit;
    } else {
        header("Location: index.php?adm=13&error=6");
    }
}
?>