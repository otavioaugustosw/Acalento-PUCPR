<?php
session_start();
include '../teste_conexao/conexao.php';
$conexao = conecta_db();

// Verifica se usuário está "logado"
if (!isset($_SESSION['id_usuario'])) {
    die("Faça login primeiro!");
}

$id_evento = $_POST['id_evento'];
$id_usuario = $_SESSION['id_usuario'];

// Verifica se o evento existe
$sqlEvento = "SELECT lotacao_max FROM evento WHERE id = $id_evento";
$resultEvento = $conexao->query($sqlEvento);

if ($resultEvento->num_rows === 0) {
    header("Location: chooseEvent.php?erro=evento_inexistente");
    exit();
}

$evento = $resultEvento->fetch_object();

// Verifica se o usuário já está inscrito
$sqlVerifica = "SELECT id FROM usuario_participa_evento WHERE id_evento = $id_evento AND id_usuario = $id_usuario";
$resultVerifica = $conexao->query($sqlVerifica);

if ($resultVerifica->num_rows > 0) {
    header("Location: chooseEvent.php?erro=ja_inscrito");
    exit();
}

// Verifica se ainda tem vaga
$sqlTotalInscritos = "SELECT COUNT(*) AS total FROM usuario_participa_evento WHERE id_evento = $id_evento";
$resultTotal = $conexao->query($sqlTotalInscritos);
$total = $resultTotal->fetch_object()->total;

if ($total >= $evento->lotacao_max) {
    header("Location: chooseEvent.php?erro=evento_lotado");
    exit();
}

// Insere a inscrição
$sqlInsere = "INSERT INTO usuario_participa_evento (id_usuario, id_evento) VALUES ($id_usuario, $id_evento)";
if ($conexao->query($sqlInsere)) {
    header("Location: chooseEvent.php?sucesso=inscrito");
} else {
    header("Location: chooseEvent.php?erro=erro_ao_inscrever");
}
exit();
?>