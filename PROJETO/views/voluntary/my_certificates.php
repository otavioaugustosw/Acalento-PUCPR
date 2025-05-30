<?php
include_once (ROOT . '/components/cards/cards.php');
include_once (ROOT .  "/components/sidebars/sidebars.php");
include_once (ROOT .  "/components/back/back.php");
include_once (ROOT . '/models/voluntary_models_php.php');
include_once (ROOT . '/models/admin_models_php.php');
include_once (ROOT . '/php/config/database_php.php');
include_once (ROOT . "/components/table/tables.php");
include_once (ROOT . '/php/handlers/time_handler.php');

$conn = connectDatabase();
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
        <title> Acalento | Certificados</title>
    </head>
    <body>
    <?php make_mobile_sidebar()?>
    <div class="d-flex flex-nowrap">
        <?php make_sidebar(); ?>
        <div class="main-content">
            <main class="px-5 row">
                <div class="container-fluid">
                    <h2>Certificados</h2>
                    <?php
                    $events = get_confirmed_events($conn, $_SESSION['USER_ID']);
                    $table_head = ["Evento", "Data", "Certificado"];

                    if (!$events) {
                        showError(7);
                    } else if ($events->num_rows <= 0) {
                        echo '<h3 class="pb-2">Você ainda não foi em nenhum evento :( </h3>';
                    } else {
                        render_certificates_table($table_head, $events);
                    }
                    ?>
                </div>
        </main>
    </div>
    </div>
    </body>
    </html>