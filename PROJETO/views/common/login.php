<?php
include(ROOT . '/php/config/session_php.php');
include(ROOT . '/php/config/database_php.php');
include(ROOT . '/php/auth_services/auth_service_php.php');
include(ROOT . '/php/handlers/form_validator_php.php');

$conn = connectDatabase();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/form-style.css">
    <link rel="stylesheet" href="css/main-content.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
<div class="card shadow-lg p-4">
    <h2 class="mb-4 text-center">Entrar</h2>
    <form action="index.php?common=2" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" name="email" id="email" required placeholder="seu@email.com">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Senha</label>
            <input type="password" class="form-control" name="password" id="password" required placeholder="••••••••">
        </div>
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>
</div>
</body>
</html>
<?php
if(isset($_POST['email'], $_POST['password'])) {
    $result = authenticate_user($conn, $_POST['email'], $_POST['password']);
    displayValidation('email', isValidEmail($_POST['email']));
    displayValidation('password', hasContent($_POST['password']));
    if ($result['status']) {
        header('Location: index.php?common=6');
        exit;
    } else {
       displayValidation('email', false);
       displayValidation('password',false);
        ?>
        <div class="toast-container position-absolute p-3 top-0 start-50 translate-middle-x">
            <div class="toast text-bg-danger border-0 show" id="toastRuim">
                <div class="d-flex">
                    <div class="toast-body">
                        <?= $result['statusName'] == "BLOCK" ? "Tentativas excedidas, tente novamente mais tarde." : "E-mail ou senha incorretos."?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fechar"></button>
                </div>
            </div>
        </div>
        <?php
    }
}

?>