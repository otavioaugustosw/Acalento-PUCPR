<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . "/php/handlers/form_validator_php.php");
include_once (ROOT . "/components/sidebars/sidebars.php");
include_once (ROOT . "/models/common_models_php.php");

$conn = connectDatabase();

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
    if (!has_min_length($_POST['senha'], 8) || ($_POST['senha'] !== $_POST['confirmarSenha']) ) {
        display_validation('senha', false);
        display_validation('confirmarSenha', false);
        showError(17);
    }
    else {
        $new_password = generate_password_hash($_POST['senha']);
        $did_change_password = update_password($conn, $_SESSION['USER_ID'], $new_password);
        if ($did_change_password) {
            showSucess(10);
        }
        else {
            showError(7);
        }
    }
}
?>
