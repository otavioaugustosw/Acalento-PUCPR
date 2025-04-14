<?php
include(ROOT .'/php/config/session.php');
include(ROOT . '/php/config/database_php.php');
include(ROOT . '/php/auth_services/AuthService.php');

$sql = connectDatabase();
$auth = new AuthService($sql);

if(isset($_POST['email'], $_POST['password'])) {
    $result = $auth->authenticateUser($_POST['email'], $_POST['password']);
    if ($result['status']) {
        header('Location: index.php?common=4');
        exit;
    } else {?>
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
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link href="css/form-style.css" rel="stylesheet">
    <link href="css/default.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
<div class="card shadow-lg p-4">
    <h2 class="mb-4 text-center">Entrar</h2>
    <form action="/Acalento/projeto/index.php?common=2" method="POST">
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