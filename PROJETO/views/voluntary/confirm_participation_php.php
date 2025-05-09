<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . "/php/auth_services/auth_service_php.php");
include_once (ROOT . "/models/voluntary_models_php.php");
$conn = connectDatabase();

if (!isset($_POST['id_evento'])) {
    header("Location: index.php?voluntary=2&error=21");
    exit();
}

$event_id = $_POST['id_evento'];
$event = get_events_where($conn, "WHERE evento.id = $event_id", $_SESSION['USER_ID']);

if ($event->num_rows === 0) {
    header("Location: index.php?voluntary=2&error=21");
    exit();
}

$event = $event->fetch_object();

if (!$event->esta_inscrito){
    header("Location: index.php?voluntary=2&error=21");
    exit();
}

if (!$event->confirmacao) {
    $result = toggle_voluntary_participation_event($conn, $_SESSION['USER_ID'], $event_id);

    if ($result){
        load_user_session_data($conn);
        header("Location: index.php?voluntary=2&success=21");
        exit();
    }
    else {
        header("Location: index.php?voluntary=2&error=21");
        exit();
    }
}
