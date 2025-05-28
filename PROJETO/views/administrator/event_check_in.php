<?php
include_once (ROOT . '/components/cards/cards.php');
include_once (ROOT .  "/components/sidebars/sidebars.php");
include_once (ROOT . '/models/voluntary_models_php.php');
include_once (ROOT . '/models/admin_models_php.php');
include_once (ROOT . '/php/config/database_php.php');
include_once (ROOT . "/components/table/tables.php");
include_once (ROOT . '/php/handlers/time_handler.php');
include_once (ROOT .  "/components/back/back.php");

if (!isset($_GET['id'])) {
    ?> <script> history.back() </script> <?php
}

$conn = connectDatabase();
$event = get_events_where($conn,"WHERE evento.id =" . $_GET['id'] , $_SESSION['USER_ID']);
if (!$event) {
    showError(7);
} else {
    $event = $event->fetch_object();
}
$volunteers = get_event_voluntary($conn, $event->id);
$table_head = ["Nome", "Presente"];

if (isset($_GET['presenca'])) {
    toggle_voluntary_presence_event($conn, $_GET['iduser'], $event->id);
    $volunteers = get_event_voluntary($conn, $event->id);;
}
else if (isset($_GET['endevent'])) {
    end_event($conn, $event->id);
    $event = get_events_where($conn,"WHERE evento.id =" . $_GET['id'] , $_SESSION['USER_ID'])->fetch_object();;
}
?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/default.css">
        <link rel="stylesheet" href="css/sidebar.css">
        <link rel="stylesheet" href="css/main-content.css">
        <link rel="stylesheet" href="css/form-style.css">
        <link rel="stylesheet" href="css/cards.css">
        <title>Gerenciar punições</title>
    </head>
    <body>
    <?php make_mobile_sidebar()?>
    <div class="d-flex flex-nowrap">
        <?php make_sidebar(); ?>
        <div class="main-content">
            <main class="px-5 row">
                <div class="container-fluid">
                    <h2>Check-in <?= $event->nome ?></h2>
                    <?php
                    if (!$volunteers) {
                        showError(7);
                    }
                    else if ($volunteers->num_rows <= 0) {
                        echo '<h3 class="pb-2">Nenhum voluntário confirmou presença.</h3>';
                    }
                    else if ($event->finalizado) {
                        echo '<h3 class="pb-2">Evento finalizado</h3>';
                        render_checkin_table($table_head, $volunteers, $event, disable: true);
                    }
                    else if ($event->eh_dia_evento && !$event->evento_comecou) {
                        echo '<h3 class="pb-2">Aguarde o horário para realizar check-in</h3>';
                        render_checkin_table($table_head, $volunteers, $event, disable: true);
                    }
                    else if (!has_event_already_occurred($event)) {
                        echo '<h3 class="pb-2">Voluntários confirmados até agora</h3>';
                        render_checkin_table($table_head, $volunteers, $event, disable: true);
                    }
                    else {
                    render_checkin_table($table_head, $volunteers, $event);
                    ?>
                    <div class="d-flex w-100 mt-5">
                        <?=
                        makeModal(
                            $event->id,
                            button_text: 'Encerrar evento',
                            modal_title: 'Encerrar evento',
                            modal_body: "Deseja encerrar evento? Os voluntários ausentes serão punidos e não será possivel realizar check novamente.",
                            confirm_text: 'Sim, encerrar',
                            form_action: "index.php?adm=13&endevent=1&id=$event->id",
                        );
                        }?>
                    </div>
                </div>
        </div>
        </main>
    </div>
    </div>
    </body>
    </html>
<?php


