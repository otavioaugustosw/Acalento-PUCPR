<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . "/components/sidebars/sidebars.php");
include_once (ROOT . "/components/table/tables.php");
include_once (ROOT . "/components/cards/cards.php");
include_once (ROOT . "/models/donator_models_php.php");
include_once (ROOT .  "/components/back/back.php");
include_once (ROOT . "/php/handlers/filter_php.php");
include_once (ROOT . "/components/filter/filter.php");

$conn = connectDatabase();
$donation = get_donations_to_validate($conn, set_where_validate());
$table_head = ["Usuário", "Valor", "Comprovante", "Validar"];
if (isset($_GET['validado'], $_GET['id_donation'])) {
    $status = intval($_GET['validado']);
    $id = intval($_GET['id_donation']);
    validate_donation($conn, $status, $id);
    $donation = get_donations_to_validate($conn, set_where_validate()
    );

}

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
    <title>Acalento | Validar pagamento</title>
</head>

<body>
<?php make_mobile_sidebar() ?>
<div class="d-flex flex-nowrap">
    <?php make_sidebar(); ?>
    <div class="main-content">
        <main class="px-5 row addScroll">
            <div class="container-fluid">
                <div class="mb-3">
                    <?php make_buttom_back("");?>
                    <h2>Validar doações monetárias</h2>
                    <?php make_filter_validate();
                    render_validate_donation_table($table_head, $donation) ?>
                </div>
            </div>
        </main>
    </div>
</div>