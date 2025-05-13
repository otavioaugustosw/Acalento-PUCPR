<?php
include_once (ROOT . '/components/cards/cards.php');
include_once (ROOT .  "/components/sidebars/sidebars.php");
include_once (ROOT . '/models/voluntary_models_php.php');
include_once (ROOT . '/php/config/database_php.php');

if (!isset($_GET['id'])) {
    ?> <script> history.back() </script> <?php
}

$conn = connectDatabase();
$event = get_events_where($conn,"WHERE evento.id =" . $_GET['id'], $_SESSION['USER_ID']);
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
    <title>Acalento | Editar evento</title>
</head>

<body>
<!-- monta a sidebar mobile -->
<?php make_mobile_sidebar() ?>
<div class="d-flex flex-nowrap">
    <!--    monta a sidebar desktop-->
    <?php make_sidebar(); ?>
    <!-- fim sidebar -->

    <!-- conteudo -->
    <div class="main-content">
        <main class="px-5 row">
            <div class="container-fluid ">
                <div class="pb-5">
                    <?php make_buttom_back(); ?>

                </div>
                <?php
                if (!$event) {
                    showError(7);
                }
                if ($event->num_rows <= 0) {
                    echo '<h3>Nenhum evento cadastrado</h3>';
                }
                else {
                    render_event_detail_card($event->fetch_object());
                }
                ?>
            </div>
        </main>
    </div>
</div>
</body>
</html>