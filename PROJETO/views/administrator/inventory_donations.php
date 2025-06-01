<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT .  "/components/sidebars/sidebars.php");
include_once (ROOT . "/components/filter/filter.php");
include_once (ROOT . "/php/handlers/filter_php.php");
include_once (ROOT . "/components/table/tables.php");
include_once (ROOT . "/models/donator_models_php.php");
include_once (ROOT .  "/components/back/back.php");

$conn = connectDatabase();
$inventory_donations = get_donations_where($conn, "WHERE doacao.id_estoque IS NOT NULL ORDER BY doacao.id");
$table_head1 = ["Item", "Quantidade", "Tipo", "Doador", "Data da doação", "Destino"];
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
    <title>Acalento | Doações</title>
</head>
<body>
<?php make_mobile_sidebar() ?>
<div class="d-flex flex-nowrap">
    <?php make_sidebar(); ?>
    <div class="main-content">
        <main class="px-5 row addScroll">
            <div class="container-fluid">
                <?php make_buttom_onclick(); ?>
                <h2>Doações em estoque</h2>
                <?php
                makeFilter(true);
                    if (!$inventory_donations) {
                        showError(7);
                    }
                    if ($inventory_donations->num_rows <= 0) {
                        echo '<h3 class="d-flex justify-content-center p-5">Não há doações no estoque</h3>';
                    }
                    else {
                        render_donator_donations_table($table_head1, $inventory_donations);
                    }
                    ?>
            </div>
        </main>
    </div>
</div>
</body>
</html>

