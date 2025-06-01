<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . "/models/common_models_php.php");

$conn = connectDatabase();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $did_deactivated_user = deactivate_user($conn, $_SESSION['USER_ID']);

    if ($did_deactivated_user) {
        header("Location: index.php?common=3");
    } else {
        header("Location: index.php?common=7&error=1");
    }
}

?>
