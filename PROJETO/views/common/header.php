<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/header.css">
    <title>Acalento | Início</title>
</head>
<body>
<header class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <img src="assets/logo/acalento-logo.svg" alt="Logo" width="100" height="100" class="me-2">
        <div class="col">
            <div class="row">
                <a class="navbar-brand d-flex align-items-baseline" href="">
                    <h1>Projeto</h1>
            </div>
            <div class="row">
                <h1>Acalento</h1>
            </div>
        </div>
        </a>
        <div class="d-flex align-items-center">
            <?php if (isset($_SESSION['USER_NAME'])): ?>
                <span class="me-3 fw-bold text-primary"><?=$_SESSION['USER_NAME']?></span>
                <a href="index.php?common=3" class="btn btn-danger">Sair</a>
            <?php else: ?>
                <a href="index.php?common=2" class="btn btn-primary btn-outline-primary">Entrar</a>
                <a href="index.php?common=5" class="btn btn-primary btn-outline-primary">Cadastra-se</a>
            <?php endif; ?>
        </div>
    </div>
</header>