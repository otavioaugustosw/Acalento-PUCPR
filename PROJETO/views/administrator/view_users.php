<?php
include (ROOT . "/php/config/database_php.php");
include(ROOT . "/components/sidebars/sidebars.php");
include(ROOT . "/components/table/tables.php");
include(ROOT . "/components/cards/cards.php");
include(ROOT . "/models/common_models_php.php");

$conn = connectDatabase();

$table_head = ["Nome", "Email", "Telefone", "CPF", "Doador", "Voluntário", "Administrador", "Suspender", "Inativar"];
$filterUsers = get_users_where($conn, "");

if (isset($_GET['id_user'])) {

    if (isset($_GET['suspender'])) {
        if ($_GET['suspender']) {
           echo apply_user_suspension($conn, $_GET['id_user'], true) ? "suspendeu" : "não suspendeu";
        }
        else {
            echo retire_user_suspension($conn, $_GET['id_user']) ? "retirou" : "não retirou";
        }
    }

    if (isset($_GET['inativar'])) {
        if ($_GET['inativar']) {
            deactivate_user($conn, $_GET['id_user']);
        }
        else {
            reactivate_user($conn, $_GET['id_user']);
        }
    }
    $filterUsers = get_users_where($conn, "");
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
                        <?php
                        if (!$filterUsers) {
                            showError(7);
                        }
                        if ($filterUsers->num_rows <= 0) {
                            echo '<h3>Nenhum usuário cadastrado</h3>';
                        }
                        else {
                            render_users_table($table_head, $filterUsers);
                        }
                        ?>
                    </div>
                </div>
        </main>
    </div>
</div>
</body>
</html>
