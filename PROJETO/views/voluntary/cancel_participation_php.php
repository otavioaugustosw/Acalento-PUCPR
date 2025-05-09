<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . "/models/voluntary_models_php.php");
include_once (ROOT . "/models/common_models_php.php");

$conn = connectDatabase();

// Verifica se o usuário está logado
if (!isset($_SESSION['USER_ID'])) {
    showError(11);
}

if (!isset($_POST['id_evento'])) {
    header("Location: index.php?voluntary=2&error=12");
    exit();
}

$event_id = $_POST['id_evento'];
$event = get_events_where($conn, "WHERE evento.id = $event_id", $_SESSION['USER_ID']);

// Verifica se o evento existe
if ($event->num_rows === 0) {
    header("Location: index.php?voluntary=2&error=12");
    exit();
}

// Verifica se o usuário realmente está inscrito
$event = $event->fetch_object();

if (!$event->esta_inscrito){
    header("Location: index.php?voluntary=2&error=21");
    exit();
}

$isPunishable = is_event_in_days($event, 3);

if ($isPunishable) {
    $result = add_user_punishment($conn, $_SESSION['USER_ID'], "faltou ao evento $event->nome", event_id: $event_id);
    if (!$result){
        header("Location: index.php?voluntary=2&error=12");
        exit();
    }
}

// Cancela a inscrição
$did_cancel_subscription = cancel_user_event_subscription($conn, $event_id, $_SESSION['USER_ID']);
if ($did_cancel_subscription) {
    $isPunishable ? header("Location: index.php?voluntary=2&error=22") : header("Location: index.php?voluntary=2&success=6");
} else {
    header("Location: index.php?voluntary=2&error=12");
}

