<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . "/models/common_models_php.php");

$conn = connectDatabase();;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['inativar'])) {
    $query = "UPDATE usuario SET inativo = 1 WHERE id = " . $_SESSION['USER_ID'];
    $resultado = $conn->query($query);

    if ($resultado) {
        header("Location: index.php?common=7&success=20");
    } else {
        showError(1);
        header("Location: index.php?common=7&error=1");
    }
}
?>
