<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT .  "/components/sidebars/sidebars.php");
include_once (ROOT . "/components/filter/filter.php");
include_once (ROOT . "/php/handlers/filter_php.php");
include_once (ROOT . "/components/table/tables.php");
include_once (ROOT . "/models/admin_models_php.php");
include_once (ROOT .  "/components/back/back.php");

$conn = connectDatabase();
$where = " WHERE up.id_usuario !=" . $_SESSION['USER_ID'];
$punishments = get_all_punishments($conn, $where);
$table_head = ["ID", "Nome", "Email", "Data", "Evento", "Status", "Revisar"];

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
    <title>Gerenciar punições</title>
</head>
<body>
<?php make_mobile_sidebar() ?>
<div class="d-flex flex-nowrap">
    <?php make_sidebar(); ?>
    <div class="main-content">
        <main class="px-5 row">
            <?php make_buttom_back("index.php?common=6");?>

            <div class="container-fluid">
                <h2>Gerenciar penalidades</h2>
                <?php
                makeFilter();
                if (!$punishments) {
                    showError(7);
                }
                if ($punishments->num_rows <= 0) {
                    echo '<h3 class="d-flex justify-content-center p-5">Nenhuma penalidade registrada.</h3>';
                }
                else {
                    render_punishments_table($table_head, $punishments);
                }?>
            </div>
    </div>
    </main>
</div>
</div>
</body>
</html>
