<?php
session_start();
include '../teste_conexao/conexao.php';
$conexao = conecta_db();

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    die("Faça login primeiro!");
}

$id_evento = $_POST['id_evento'];
$id_usuario = $_SESSION['id_usuario'];

// Verifica se o usuário realmente está inscrito
$sqlVerifica = "SELECT id FROM usuario_participa_evento WHERE id_evento = $id_evento AND id_usuario = $id_usuario";
$resultVerifica = $conexao->query($sqlVerifica);

if ($resultVerifica->num_rows === 0) {
    // Não está inscrito, não tem o que cancelar
    header("Location: chooseEvent.php?erro=erro_ao_cancelar");
    exit();
}

// Cancela a inscrição
$sqlDelete = "DELETE FROM usuario_participa_evento WHERE id_evento = $id_evento AND id_usuario = $id_usuario";
if ($conexao->query($sqlDelete)) {
    header("Location: chooseEvent.php?sucesso=cancelado");
} else {
    header("Location: chooseEvent.php?erro=erro_ao_cancelar");
}
exit();
?>
