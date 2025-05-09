<?php
include (ROOT . "/php/config/database_php.php");
include(ROOT . "/php/auth_services/auth_service_php.php");
include(ROOT . "/php/handlers/form_validator_php.php");
include(ROOT . "/components/sidebars/sidebars.php");
$conn = connectDatabase();

$id_usuario = $_SESSION['USER_ID'] ?? null;

if (!$id_usuario) {
    die("<div class='alert alert-danger'>Usuário não autenticado</div>");
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
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/main-content.css">
    <title>Alterar senha</title>
</head>


<body>
<?php make_mobile_sidebar() ?>
<div class="d-flex flex-nowrap">
    <?php make_sidebar() ?>
    <main class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-3">
            <h2 class="my-4">Alterar a Senha</h2>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nova Senha*</label>
                    <input type="password" name="senha" id="senha" class="form-control border-dark-subtle" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirmar Nova Senha*</label>
                    <input type="password" name="confirmarSenha" id="confirmarSenha" class="form-control border-dark-subtle" required>
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
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hasMinLength($_POST['senha'], 8) || ($_POST['senha'] !== $_POST['confirmarSenha']) ) {
        displayValidation('senha', false);
        displayValidation('confirmarSenha', false);
        showError(17);
        return false;
    } else {
        $senha = generate_password_hash($_POST['senha']);
        $query = "UPDATE usuario SET senha = '$senha' WHERE id = $id_usuario";
        if ($conn->query($query)) {
            showSucess(10);
            header("Location: index.php?common=7");
            exit;
        } else {
            echo "<div class='alert alert-danger'>Erro ao atualizar senha: " . $conn->error . "</div>";
        }
    }
}
?>
