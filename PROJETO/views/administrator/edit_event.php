<?php
include(ROOT . "/php/config/database_php.php");
include(ROOT . "/php/handlers/filter_php.php");
include(ROOT . "/components/filter/filter.php");
include(ROOT . "/components/cards/cards.php");
include(ROOT .  "/components/sidebars/sidebars.php");
include(ROOT .  "/models/voluntary_models_php.php");

$conn = connectDatabase();
$subscribed_events = get_subscribed_events($conn, $_SESSION['USER_ID']);
$events = get_events_where($conn, setWhere('evento'));
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
        <main class="px-5 row addScroll">
            <div class="container-fluid">
                <div class="mb-3">
                    <!-- aqui vai o que você quer por -->
                    <h2>Eventos</h2>
                    <?php makeFilter();?>
                    <div class="row row-cols-1 row-cols-sm-1 row-cols-md-1 row-cols-lg-2 row-cols-xl-2 row-cols-xxl-3 g-5 main">
                        <?php
                        if (!$events) {
                            showError(7);
                        }
                        if ($events->num_rows <= 0) {
                            echo '<h3>Nenhum evento cadastrado</h3>';
                        }
                        else {
                            render_events_card($events, $subscribed_events , admin: true);
                        }
                        ?>
                    </div>
                    <!-- aqui termina -->
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
