<?php
include (ROOT . "/php/config/database_php.php");

$conexao = connectDatabase();;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['inativar'])) {
    $query = "UPDATE usuario SET status = 1 WHERE id = " . $_SESSION['USER_ID'];
    $resultado = $conexao->query($query);

    if ($resultado) {
        header("Location: index.php?common=7&success=20");
    } else {
        showError(1);
        header("Location: index.php?common=7&error=1");
    }
}
?>
