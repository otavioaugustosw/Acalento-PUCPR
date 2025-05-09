<?php
include (ROOT . "/php/config/database_php.php");
include (ROOT . "/php/auth_services/auth_service_php.php");
$conn = connectDatabase();

// Verifica se usuário está "logado"
if (!isset($_SESSION['USER_ID'])) {
    showError(11);
}

$id_evento = $_POST['id_evento'];
$id_usuario = $_SESSION['USER_ID'];

// Verifica se o evento existe
$sqlEvento = "SELECT lotacao_max FROM evento WHERE id = $id_evento";
$resultEvento = $conn->query($sqlEvento);

if ($resultEvento->num_rows === 0) {
    header("Location: index.php?voluntary=2");
    exit();
}

$evento = $resultEvento->fetch_object();

// Verifica se o usuário já está inscrito
$sqlVerifica = "SELECT id FROM usuario_participa_evento WHERE id_evento = $id_evento AND id_usuario = $id_usuario";
$resultVerifica = $conn->query($sqlVerifica);

if ($resultVerifica->num_rows > 0) {
    exit();
}

// Verifica se ainda tem vaga
$sqlTotalInscritos = "SELECT COUNT(*) AS total FROM usuario_participa_evento WHERE id_evento = $id_evento";
$resultTotal = $conn->query($sqlTotalInscritos);
$total = $resultTotal->fetch_object()->total;

if ($total >= $evento->lotacao_max) {
    header("Location: index.php?voluntary=5&error=13");
    exit();
}

// Insere a inscrição
$sql_insere = "INSERT INTO usuario_participa_evento (id_usuario, id_evento) VALUES ($id_usuario, $id_evento);";
$sql_update_voluntario = "UPDATE usuario SET eh_voluntario = 1";
if ($conn->query($sql_insere) && $conn->query($sql_update_voluntario)) {
    load_user_session_data($conn);
    header("Location: index.php?voluntary=5&success=7");
} else {
    header("Location: index.php?voluntary=4&error=14");
}
exit();
?>