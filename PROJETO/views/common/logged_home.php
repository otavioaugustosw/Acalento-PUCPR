<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . "/components/sidebars/sidebars.php");
include_once (ROOT . "/components/table/tables.php");
include_once (ROOT . "/components/cards/cards.php");
include_once (ROOT . "/models/donator_models_php.php");
include_once (ROOT . "/models/voluntary_models_php.php");
include_once (ROOT . "/php/handlers/filter_php.php");
include_once(ROOT . "/php/handlers/badge_handler.php");
include_once (ROOT . "/components/carousel/carousel.php");

$conn = connectDatabase();
$next_events = get_events_where($conn, 'WHERE evento.data >= NOW() AND evento.inativo = 0 ORDER BY evento.id DESC LIMIT 3;',  $_SESSION['USER_ID']);
$all_my_events = get_total_participation_events($conn, $_SESSION['USER_ID']);
$last_events = get_last_event($conn, $_SESSION['USER_ID']);
$all_donations = get_donations_where($conn, "ORDER BY doacao.id DESC LIMIT 10");
$all_my_donations = get_donations_where_badges($conn, $_SESSION['USER_ID']);
$last_donations = get_last_donation($conn, $_SESSION['USER_ID']);
$my_donations = get_all_donations($conn, 'WHERE doacao.id_usuario = ' . $_SESSION['USER_ID'],
    'WHERE doacao_monetaria.id_usuario = ' . $_SESSION['USER_ID'], 'ORDER BY data DESC LIMIT 5');
$table_head = ["Item", "Quantidade", "Tipo", "Doador", "Data da doação", "Destino"];
$table_head1 = ["Doador", "Tipo", "Doação", "Data"]; ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/main-content.css">
    <link rel="stylesheet" href="css/cards.css">
    <link rel="stylesheet" href="css/card-reveal.css">
    <link rel="stylesheet" href="css/badges.css">
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
                        <h2 class="p-0 mb-1">Bem vindo!!!</h2>
                        <h5 class="p-0" style="font-weight: 550">Obrigado por fazer parte do acalento</h5>
                        <?php
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

                        // voluntário e doador
                        } elseif ($_SESSION['USER_IS_VOLUNTARY'] && $_SESSION['USER_IS_DONATOR']) { ?>
                                <div class="col-md-6 ps-0">
                                    <?php donator_badges($all_my_donations);
                                    last_donation_bagde($last_donations); ?>
                                </div>
                                <div class="col-md-6 pe-0">
                                    <?php voluntary_badge($all_my_events);
                                    last_events_badge($last_events); ?>
                                </div>

                                <h2 class="p-0">Suas últimas doações</h2>
                                <?php
                                if (!$my_donations) {
                                    showError(7);
                                }
                                if ($my_donations->num_rows <= 0) {
                                    echo '<h3 class="p-5">Nenhuma doação encontrada.</h3>';
                                } else {
                                    render_all_donations_table($table_head1, $my_donations);
                                } ?>
                                <h2 class="p-0 mb-1">Próximos eventos</h2>
                                <?php
                                if (!$next_events) {
                                    showError(7);
                                } elseif ($next_events->num_rows <= 0) {
                                    echo '<h3>Nenhum evento cadastrado</h3>';
                                } else {
                                    make_cards_carousel($next_events);
                                } ?>
                            <?php

                        // só doador
                        } elseif ($_SESSION['USER_IS_DONATOR']) {
                            donator_badges($all_my_donations);
                            last_donation_bagde($last_donations);?>

                            <h2 class="p-0">Suas últimas doações</h2>
                            <?php
                            if (!$my_donations) {
                                showError(7);
                            }
                            if ($my_donations->num_rows <= 0) {
                                echo '<h3 class="p-5">Nenhuma doação encontrada.</h3>';
                            } else {
                                render_donator_donations_table($table_head1, $my_donations);
                            }
                            ?>

                            <h2 class="p-0 mb-1">Próximos eventos</h2>
                            <h5 class="p-0" style="font-weight: 550">Seja um voluntário!!</h5>
                            <?php
                            if (!$next_events) {
                                showError(7);
                            } elseif ($next_events->num_rows <= 0) {
                                echo '<h3>Nenhum evento cadastrado</h3>';
                            } else {
                                make_cards_carousel($next_events);
                            }

                        // voluntário
                        } elseif ($_SESSION['USER_IS_VOLUNTARY']) {
                            voluntary_badge($all_my_events);
                            last_events_badge($last_events);
                            ?>

                            <h2 class="p-0 mb-1">Próximos eventos</h2>
                            <?php
                            if (!$next_events) {
                                showError(7);
                            } elseif ($next_events->num_rows <= 0) {
                                echo '<h3>Nenhum evento cadastrado</h3>';
                            } else {
                                make_cards_carousel($next_events);
                            } ?>

                            <h2 class="p-0 mb-1">Doe!!</h2>
                            <h5 class="p-0" style="font-weight: 550">Seja um doador!!</h5>
                            <div class="row p-0">
                                <div class="col-md-6">
                                    <?php make_event_card_reveal("Doação Material", "Doe itens e transforme recursos em esperança para quem mais precisa!",
                                    "Faça a sua doação", "#", "btn btn-primary largura-completa", "assets/imagens/default.jpg");?>
                                </div>
                                <div class="col-md-6">
                                    <?php make_event_card_reveal("Doação Monetária", "Faça a sua doação e ajude centenas de crianças a terem um futuro melhor!",
                                        "Faça a sua doação", "#", "btn btn-primary largura-completa", "assets/imagens/default.jpg");?>
                                </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
