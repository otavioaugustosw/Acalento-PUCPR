<?php
include (ROOT . "/php/config/database_php.php");
include(ROOT . "/components/sidebars/sidebars.php");
include(ROOT . "/components/table/tables.php");
include(ROOT . "/components/cards/cards.php");
include(ROOT . "/models/donator_models_php.php");
include(ROOT . "/models/voluntary_models_php.php");
include (ROOT . "/php/handlers/filter_php.php");

$conn = connectDatabase();
$next_events = get_events_where($conn, 'WHERE evento.data >= NOW() AND evento.inativo = 0 ORDER BY evento.id DESC LIMIT 2;');
$last_events = get_events_where($conn, 'WHERE evento.data < NOW() AND evento.inativo = 0 LIMIT 2;');
$subscribed_events = get_subscribed_events($conn, $_SESSION['USER_ID']);
$all_donations = get_donations_where($conn, "ORDER BY doacao.id DESC LIMIT 10");
$my_donations = get_donations_where($conn, set_where_donations('myown'));
$table_head = ["Item", "Quantidade", "Tipo", "Doador", "Data da doação", "Destino"];?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/main-content.css">
    <link rel="stylesheet" href="css/cards.css">
    <title>Acalento | Home</title>
</head>

<body>
<?php make_mobile_sidebar() ?>
<div class="d-flex flex-nowrap">
    <?php make_sidebar(); ?>
    <div class="main-content flex-grow-1">
        <main class="px-5 row addScroll py-5">
            <div class="container-fluid">
                <div class="mb-3">
                    <div class="row">
                        <?php
                        if (!$_SESSION['USER_IS_ADMINISTRATOR']) { ?>
                        <div class="col-md-6 px-4">
                            <h2>Suas últimas doações</h2>
                            <?php
                            if (!$my_donations) {
                                showError(7);
                            }

                            if ($my_donations->num_rows <= 0) {
                                echo '<h3 class="p-5">Nenhuma doação encontrada.</h3>';
                            } else {
                                render_donator_donations_table($table_head, $my_donations);

                                }
                            ?>
                        </div>

                        <div class="col-md-6 px-4">
                            <h2>Próximos eventos</h2>
                            <?php
                            if (!$next_events) {
                                showError(7);
                            }
                            if ($next_events->num_rows <= 0) {
                                echo '<h3>Nenhum evento cadastrado</h3>';
                            } else {
                                render_events_card($next_events, $subscribed_events , voluntary: true, horizontal: true);
                            }
                        }

                        if ($_SESSION['USER_IS_ADMINISTRATOR']) { ?>
                        <div class="col-md-6">
                            <h2>Últimas doações recebidas</h2>
                            <?php
                            if (!$all_donations) {
                                showError(7);
                            }

                            if ($all_donations->num_rows <= 0) {
                                echo '<h3 class="p-5">Nenhuma doação encontrada.</h3>';
                            } else {
                                render_donator_donations_table($table_head, $all_donations);
                            }
                            ?>
                        </div>

                        <div class="col-md-6 px-4">
                            <h2>Últimos eventos cadastrados</h2>
                            <?php
                            if (!$next_events) {
                                showError(7);
                            }
                            if ($next_events->num_rows <= 0) {
                                echo '<h3>Nenhum evento cadastrado</h3>';
                            } else {
                                render_events_card($next_events, admin: true, horizontal: true);
                            }
                            }
                            ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
