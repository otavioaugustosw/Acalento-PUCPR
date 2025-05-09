<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . "/components/sidebars/sidebars.php");
include_once (ROOT . "/components/table/tables.php");
include_once (ROOT . "/components/cards/cards.php");
include_once (ROOT . "/models/donator_models_php.php");

$conn = connectDatabase();
$all_donations = get_donations_where($conn, "ORDER BY doacao.id DESC LIMIT 5");
$inventory_donations = get_donations_where($conn, "WHERE doacao.id_estoque IS NOT NULL ORDER BY doacao.id DESC LIMIT 5");
$inventory_donations = get_donations_where($conn, "WHERE doacao.id_estoque IS NOT NULL ORDER BY doacao.id DESC LIMIT 5");
$all_campaigns = get_campaigns_where($conn, "ORDER BY campanha_doacao.id DESC LIMIT 4");
$table_head = ["Item", "Quantidade", "Tipo", "Doador", "Data da doação", "Destino"];

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/form-style.css">
    <link rel="stylesheet" href="css/cards.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/main-content.css">
    <title>Acalento | Eventos</title>
</head>

<body>
<?php make_mobile_sidebar() ?>
<div class="d-flex flex-nowrap">
    <?php make_sidebar(); ?>
    <div class="main-content">
        <main class="px-5 row addScroll">
            <div class="container-fluid">
                <div class="mb-3">
                    <div class="doacoes">
                        <div class="d-flex justify-content-between">
                            <h2>Todas as doações</h2>
                            <a class="btn btn-primary" href="index.php?adm=9">Ver todas as doações</a>
                        </div>
                        <?php
                        if (!$all_donations) {
                            showError(7);
                        }
                        if ($all_donations->num_rows <= 0) {
                            echo '<h3 class="d-flex justify-content-center p-5">Nenhuma doação encontrada</h3>';
                        }
                        else {
                            render_donator_donations_table($table_head, $all_donations);
                        }
                        ?>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between">
                            <h2>Doações em estoque</h2>
                            <a class="btn btn-primary" href="index.php?adm=11">Ver todas as doações em estoque</a>
                        </div>
                        <?php
                        if (!$inventory_donations) {
                            showError(7);
                        }
                        if ($inventory_donations->num_rows <= 0) {
                            echo '<h3 class="d-flex justify-content-center p-5">Não há doações no estoque</h3>';
                        }
                        else {
                            render_donator_donations_table($table_head, $inventory_donations);
                        }
                        ?>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between">
                            <h2>Doações por campanha</h2>
                            <a class="btn btn-primary" href="index.php?adm=8">Ver as doações por campanha</a>
                        </div>
                        <div class="row row-cols-1 row-cols-sm-1 row-cols-md-1 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-4 g-4">
                            <?php
                            if (!$all_campaigns) {
                                showError(7);
                            }
                            if ($all_campaigns->num_rows <= 0) {
                                echo '<h3 class="d-flex justify-content-center p-4">Nenhuma campanha cadastrada</h3>';
                            }
                            else {
                                render_campaigns_card($all_campaigns);
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

