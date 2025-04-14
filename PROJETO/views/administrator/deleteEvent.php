<?php
include (ROOT . "/php/config/database_php.php"); // Inclui a conexão se ainda não tiver

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Segurança básica
    $obj = connectDatabase();

    $query = "DELETE FROM evento WHERE id = $id";
    $resultado = $obj->query($query);

    if ($resultado) {
        // Redireciona para a página de listagem após deletar
        header("Location: editEvent.php?mensagem=deletado");
        exit;
    } else {
        echo "Erro ao deletar: " . $obj->error;
    }
} else {
    echo "ID do evento não foi fornecido!";
}
?>