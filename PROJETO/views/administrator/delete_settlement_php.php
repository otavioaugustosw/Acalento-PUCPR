<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . "/models/admin_models_php.php");

$conn = connectDatabase();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    list($id_estoque, $id_assentamento) = explode(',', $_POST['inativar']);
    $did_soft_delete = soft_delete_inventory_settlement($conn, $id_estoque, $id_assentamento);

    if ($did_soft_delete) {
        header("Location: index.php?adm=8&success=25");
    } else {
        header("Location: index.php?adm=8&error=41");
    }
    exit;
}