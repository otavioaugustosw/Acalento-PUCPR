<?php
include_once (ROOT . '/components/progress-bar/progress-bar.php');
include_once (ROOT . '/php/config/session_php.php');
include_once (ROOT . '/php/config/database_php.php');
include_once (ROOT . '/php/auth_services/auth_service_php.php');
include_once (ROOT . '/php/handlers/form_validator_php.php');


$conn = connectDatabase();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acalento | Início</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/main-content.css">
    <link rel="stylesheet" href="css/progress-bar.css">
    <link rel="stylesheet" href="css/form-style.css">
</head>
<body>
<div class="position-fixed top-0 start-0 w-100 z-3 py-3">
    <?php render_progress_bar(1); ?>
</div>

<!-- Conteúdo centralizado -->
<div class="d-flex justify-content-center align-items-center min-vh-100 pt-5">
    <div class="row shadow rounded overflow-hidden" style="width: 65%; height: 60vh">
        <!-- Lado esquerdo -->
        <div class="col-md-6 bg-login-left d-flex flex-column justify-content-center align-items-center p-5" style="background-color: var(--secondary-background)">
            <h2 class="text-center mb-4">Ainda não faz parte do acalento?</h2>
            <a href="index.php?common=14" class="btn btn-primary largura-50 px-5 py-2">Cadastre-se</a>
        </div>

        <!-- Lado direito -->
        <div class="col-md-6 bg-white d-flex flex-column justify-content-center p-5">
            <h2 class="text-center mb-4">Seja bem-vindo!</h2>
            <form action="index.php?common=11" method="POST">
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
    </div>
</div>
</body>
</html>
<?php
if(isset($_POST['email'], $_POST['password'])) {
    $result = authenticate_user($conn, $_POST['email'], $_POST['password']);
    display_validation('email', is_valid_email($_POST['email']));
    display_validation('password', has_content($_POST['password']));
    if ($result['status']) {
        header('Location: index.php?common=12');
        exit;
    } else {
        display_validation('email', false);
        display_validation('password',false);
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

