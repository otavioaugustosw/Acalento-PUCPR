<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . "/components/sidebars/sidebars.php");
include_once (ROOT . "/components/filter/filter.php");
include_once (ROOT . "/php/handlers/filter_php.php");
include_once (ROOT . "/components/table/tables.php");
include_once (ROOT . "/models/donator_models_php.php");
include_once (ROOT . "/components/back/back.php");

$conn = connectDatabase();

switch ($_GET['view'] ?? null){
    case 'adm':
        $page_name = "Todas doações";
        break;
    default:
        $page_name = "Minhas doações";
        break;
}

$view = $_GET['view'] ?? null;

$filtro = isset($_POST['filter_donation']) && in_array($_POST['filter_donation'], ['material', 'monetario', 'todos'])
    ? $_POST['filter_donation']
    : 'todos';

$where = set_where_donation($view, $filtro);

$all_donations = get_all_donations($conn, $where['where_material'], $where['where_monetario'], 'ORDER BY data');
$table_head1 = ["Doador", "Tipo", "Doação", "Data"];
$monetary_donatios = get_monetary_donations($conn, $where['where_monetario']);
$table_head2 = ["Doador", "Valor", "Data"];
$material_donations = get_donations_where($conn, $where['where_material']);
$table_head3 = ["Doador", "Item", "Quantidade", "Tipo", "Data da doação", "Destino"];
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
<?php make_mobile_sidebar(); ?>
<div class="d-flex flex-nowrap">
    <?php make_sidebar(); ?>
    <div class="main-content">
        <main class="px-5 row addScroll d-flex flex-column align-items-start">
            <div class="container-fluid">
                <?php make_buttom_onclick(); ?>
                <h2> <?= $page_name ?> </h2>
                <?php makeFilter(true, true); ?>

                <?php
                switch ($filtro) {
                    case 'material':
                        if (!$material_donations) {
                            showError(7);
                        } elseif ($material_donations->num_rows <= 0) {
                            echo '<h3 class="text-center p-5">Nenhuma doação encontrada</h3>';
                        } else {
                            render_donator_donations_table($table_head3, $material_donations);
                        }
                        break;

                    case 'monetario':
                        if (!$monetary_donatios) {
                            showError(7);
                        } elseif ($monetary_donatios->num_rows <= 0) {
                            echo '<h3 class="text-center p-5">Nenhuma doação encontrada</h3>';
                        } else {
                            render_monetary_donations_table($table_head2, $monetary_donatios);
                        }
                        break;

                    case 'todos':
                    default:
                        if (!$all_donations) {
                            showError(7);
                        } elseif ($all_donations->num_rows <= 0) {
                            echo '<h3 class="text-center p-5">Nenhuma doação encontrada</h3>';
                        } else {
                            render_all_donations_table($table_head1, $all_donations);
                        }
                        break;
                }
                ?>
            </div>
        </main>
    </div>
</div>
</body>
</html>