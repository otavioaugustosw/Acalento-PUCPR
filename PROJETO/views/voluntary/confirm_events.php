<?php
// todos includes e queries sempre no cabeçalho do código
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . "/components/cards/cards.php");
include_once (ROOT .  "/components/sidebars/sidebars.php");
include_once (ROOT .  "/models/voluntary_models_php.php");

$conn = connectDatabase();
$events = get_events_where($conn, " WHERE upe.id_evento = id_evento AND participacao_confirmada = 0 AND evento.data > NOW()", $_SESSION['USER_ID']);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/main-content.css">
    <link rel="stylesheet" href="css/cards.css">
    <link rel="stylesheet" href="css/form-style.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <title>Acalento | Eventos</title>
</head>
<body>
<?php make_mobile_sidebar() ?>
<div class="d-flex flex-nowrap">
    <?php make_sidebar();

    if (isset($_GET['error'])){
        showError($_GET['error']);
    }

    if (isset($_GET['success'])){
        showSucess($_GET['success']);
    }
    ?>
    <div class="main-content">
        <main class="px-5 row addScroll">
            <div class="container-fluid">
                <div class="mb-3">
                    <h2>Confirme sua participação</h2>
                    <div class="row">
                        <div class="col">
                        <?php
                        if (!$events) {
                            showError(7);
                        }
                        if ($events->num_rows <= 0) {
                            echo '<h3>Nenhum evento para confirmar</h3>';
                        }
                        else {
                            render_events_card($events , voluntary: true, horizontal: true);
                        }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
