<?php
    if(isset($_GET['id'])) {
        $obj = conecta_db();
        $query = "DELETE FROM evento WHERE id = " . $_GET['id'];
        $resultado = $obj ->query($query);
        header("Location: index.php");
    } else {
        echo "Algo deu errado";
    }
?>