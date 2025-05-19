<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . "/models/admin_models_php.php");

if (isset($_GET['id'])) {
    // a função intval tranforma o valor em um valor inteiro, está sendo usado apenas por precaução
    $event_id = intval($_GET['id']);
    $conn = connectDatabase();
    $did_delete_event = soft_delete_event($conn, $event_id);

    if ($did_delete_event) {
        header("Location: index.php?adm=5&success=4");
        exit;
    } else {
        header("Location: index.php?adm=5&error=6");
    }
}
