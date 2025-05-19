<?php
include (ROOT . "/php/config/database_php.php");
include(ROOT . "/components/sidebars/sidebars.php");
include(ROOT . "/components/table/tables.php");
include(ROOT . "/components/cards/cards.php");
include(ROOT . "/models/donator_models_php.php");

$conn = connectDatabase();

function get_users_where($conn, $where)
{
    $query = "
        SELECT nome, telefone, email, cpf, eh_doador, eh_voluntario, eh_adm, inativo, suspenso
        FROM usuario
        $where";
    return $conn->query($query);
}

$all_users = get_users_where($conn, "ORDER BY id DESC LIMIT 50");
$table_head = ["Nome", "Email", "Telefone", "CPF", "Doador", "Voluntário", "Administrador", "Suspender", "Inativar"];
$filterUsers = get_users_where($conn, "");
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

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];

    if (isset($_POST['suspender'])) {
        $stmt = $conn->prepare("UPDATE usuario SET suspenso = 1 WHERE email = ?");
        $stmt->bind_param("s", $email);
        $did_suspend = $stmt->execute();
        $did_suspend ? header('Location: index.php?adm=16') : null;

    }

    if (isset($_POST['reativar_suspenso'])) {
        $stmt = $conn->prepare("UPDATE usuario SET suspenso = 0 WHERE email = ?");
        $stmt->bind_param("s", $email);
        $did_suspend = $stmt->execute();
        $did_suspend ? header('Location: index.php?adm=16') : null;
    }

    if (isset($_POST['inativar'])) {
        $stmt = $conn->prepare("UPDATE usuario SET inativo = 1 WHERE email = ?");
        $stmt->bind_param("s", $email);
        $did_suspend = $stmt->execute();
        $did_suspend ? header('Location: index.php?adm=16') : null;
    }

    if (isset($_POST['reativar_inativo'])) {
        $stmt = $conn->prepare("UPDATE usuario SET inativo = 0 WHERE email = ?");
        $stmt->bind_param("s", $email);
        $did_suspend = $stmt->execute();
        $did_suspend ? header('Location: index.php?adm=16') : null;
    };

}
?>

