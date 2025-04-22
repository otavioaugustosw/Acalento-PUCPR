<?php
include (ROOT . "/php/config/database_php.php");
$conexao = connectDatabase();

// Verifica se o usuário está logado
if (!isset($_SESSION['USER_ID'])) {
    showError(11);
}

$id_evento = $_POST['id_evento'];
$id_usuario = $_SESSION['USER_ID'];

// Verifica se o usuário realmente está inscrito
$sqlVerifica = "SELECT id FROM usuario_participa_evento WHERE id_evento = $id_evento AND id_usuario = $id_usuario";
$resultVerifica = $conexao->query($sqlVerifica);

if ($resultVerifica->num_rows === 0) {
    exit();
}

// Cancela a inscrição
$sqlDelete = "DELETE FROM usuario_participa_evento WHERE id_evento = $id_evento AND id_usuario = $id_usuario";
if ($conexao->query($sqlDelete)) {
    header("Location: index.php?voluntary=2&success=6");
} else {
    header("Location: index.php?voluntary=2&error=12");
}
?>
