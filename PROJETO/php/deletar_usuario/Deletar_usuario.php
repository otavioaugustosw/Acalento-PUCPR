<?php
// CHUNCHO TEMPORÁRIO - ID fixo para desenvolvimento
$id_usuario = 12;

error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../conexao.php";
$conexao = conecta_db();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['inativar'])) {
    $query = "UPDATE usuario SET inativado = 1 WHERE id = $id_usuario";
    $resultado = $conexao->query($query);

    if ($resultado) {
        echo "<div class='alert alert-success'>Usuário inativado com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao inativar usuário: " . $conexao->error . "</div>";
    }
}
?>
