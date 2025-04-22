<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include (ROOT . "/php/config/database_php.php");
include (ROOT . "/php/auth_services/AuthService.php");

$conexao = connectDatabase();

$id_usuario = $_SESSION['USER_ID'] ?? null;

if (!$id_usuario) {
    die("<div class='alert alert-danger'>Usuário não autenticado</div>");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $senha = $_POST['senha'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';

    if ($senha !== $confirmar) {
        echo "<div class='alert alert-warning'>As senhas não estão iguais!</div>";
    } else {
        $senha_hash = AuthService::generatePasswordHash($senha);
        $query = "UPDATE usuario SET senha = '$senha_hash' WHERE id = $id_usuario";
        if ($conexao->query($query)) {
            echo "<div class='alert alert-success'>Senha atualizada com sucesso!</div>";
        } else {
            echo "<div class='alert alert-danger'>Erro ao atualizar senha: " . $conexao->error . "</div>";
        }
    }
    header("index.php?common=7");
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/form-style.css">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/sidebars.css">
    <link rel="stylesheet" href="css/main-content.css">
</head>


<body>
<?php include(ROOT . "/components/sidebars/sidebar-mobile.php") ?>
<div class="d-flex flex-nowrap">
    <?php include(ROOT .  "/components/sidebars/sidebars.php") ?>


    <main class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-3">
            <h2 class="my-4">Alterar a Senha</h2>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nova Senha*</label>
                    <input type="password" name="senha" class="form-control border-dark-subtle" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirmar Nova Senha*</label>
                    <input type="password" name="confirmar" class="form-control border-dark-subtle" required>
                </div>
                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary">Atualizar Senha</button>
                </div>
            </form>
        </div>
    </div>


</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>