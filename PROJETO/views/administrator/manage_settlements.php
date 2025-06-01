<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . "/php/handlers/filter_php.php");
include_once (ROOT . "/components/filter/filter.php");
include_once (ROOT . "/components/cards/cards.php");
include_once (ROOT . "/components/table/tables.php");
include_once (ROOT .  "/components/sidebars/sidebars.php");
include_once (ROOT .  "/components/back/back.php");
include_once (ROOT .  "/models/voluntary_models_php.php");
include_once (ROOT .  "/models/admin_models_php.php");
include_once (ROOT .  "/php/handlers/error_handler_php.php");

$conn = connectDatabase();
$table_head = ["Nome", "Famílias", "Rua", "Numero", "Editar", "Deletar"];
$settlements = get_settlements_info($conn);

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
    <title>Acalento | Editar assentamentos </title>
</head>

<body>
<!-- monta a sidebar mobile -->
<?php make_mobile_sidebar() ?>
<div class="d-flex flex-nowrap">
    <!--    monta a sidebar desktop-->
    <?php make_sidebar();

    if (isset($_GET['error'])){
        showError($_GET['error']);
    }

    if (isset($_GET['success'])){
        showSucess($_GET['success']);
    }
    ?>
    <!-- fim sidebar -->

    <!-- conteudo -->
    <div class="main-content">
        <main class="px-5 row addScroll">
            <div class="container-fluid">
                <div class="mb-3">
                    <!-- aqui vai o que você quer por -->
                    <div class="d-flex justify-content-between">
                        <h2>Assentamentos</h2>
                        <div class="my-5">
                            <?php makeButton("Cadastrar novo assentamento", "btn btn-primary", "index.php?adm=4"); ?>
                        </div>
                    </div>
                    <div class="row row-cols-1 row-cols-sm-1 row-cols-md-1 row-cols-lg-2 row-cols-xl-2 row-cols-xxl-3 g-5 main">
                        <?php
                        if (!$settlements) {
                            showError(7);
                        }
                        if ($settlements->num_rows <= 0) {
                            echo '<h3>Nenhum assentamento cadastrado</h3>';
                        }
                        else {
                            render_settlements_table($table_head, $settlements);
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
