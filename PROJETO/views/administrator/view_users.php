<?php
include_once(ROOT . "/php/config/database_php.php");
include_once(ROOT . "/components/sidebars/sidebars.php");
include_once(ROOT . "/components/table/tables.php");
include_once(ROOT . "/components/cards/cards.php");
include_once(ROOT . "/models/common_models_php.php");
include_once (ROOT . "/php/handlers/filter_php.php");
include_once (ROOT . "/components/filter/filter.php");



$conn = connectDatabase();
$table_head = ["Nome", "Email", "Telefone", "CPF", "Doador", "Voluntário", "Administrador", "Suspender", "Inativar"];
$all_users = get_users_where($conn, set_where_user());

if (isset($_GET['id_user'])) {

    if (isset($_GET['suspender'])) {
        if ($_GET['suspender']) {
           echo apply_user_suspension($conn, $_GET['id_user'], true) ? "suspendeu" : "não suspendeu";
        }
        else {
            echo retire_user_suspension($conn, $_GET['id_user']) ? "retirou" : "não retirou";
        }
        $all_users = get_users_where($conn, set_where_user());
    }

    if (isset($_GET['inativar'])) {
        if ($_GET['inativar']) {
            deactivate_user($conn, $_GET['id_user']);
        }
        else {
            reactivate_user($conn, $_GET['id_user']);
        }
        $all_users = get_users_where($conn, set_where_user());
    }
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
    <title>Acalento | Usuários </title>
</head>

<body>
<?php make_mobile_sidebar() ?>
<div class="d-flex flex-nowrap">
    <?php make_sidebar(); ?>
    <div class="main-content">
        <main class="px-5 row addScroll">
            <div class="container-fluid">
                <div class="mb-3">
                    <h2>Todos os usuários</h2>
                    <?php make_filter_user(); ?>
                        <?php
                        if (!$all_users) {
                            showError(7);
                        }
                        if ($all_users->num_rows <= 0) {
                            echo '<h3>Nenhum usuário cadastrado</h3>';
                        }
                        else {
                            render_users_table($table_head, $all_users);
                        }
                        ?>
                    </div>
                </div>
        </main>
    </div>
</div>
</body>
</html>