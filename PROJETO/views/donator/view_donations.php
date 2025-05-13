<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT .  "/components/sidebars/sidebars.php");
include_once (ROOT . "/components/filter/filter.php");
include_once (ROOT . "/php/handlers/filter_php.php");
include_once (ROOT . "/components/table/tables.php");
include_once (ROOT . "/models/donator_models_php.php");
$conn = connectDatabase();
$where = set_where_donations($_GET['view'] ?? null, $_GET['id'] ?? 0);
$page_name = "";

switch ($_GET['view'] ?? null){
    case 'adm':
        $page_name = "Todas doações";
        break;
    case 'inventory':
        $page_name = "Doações em estoque";
        break;
    case 'campaign':
        $page_name = "Doações da campanha";
        break;
    default:
        $page_name = "Minhas doações";
        break;
}

$donations = get_donations_where($conn, $where);
$table_head = ["Item", "Quantidade", "Tipo", "Doador", "Data da doação", "Destino"];

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
        <main class="px-5 row">
            <div class="container-fluid">
                <h2><?= $page_name ?></h2>
                <?php
                makeFilter();
                if (!$donations) {
                    showError(7);
                }
                if ($donations->num_rows <= 0) {
                    echo '<h3 class="d-flex justify-content-center p-5">Nenhuma doação encontrada.</h3>';
                }
                else {
                    render_donator_donations_table($table_head, $donations);
                }?>
            </div>
    </div>
    </main>
</div>
</div>
</body>
</html>
