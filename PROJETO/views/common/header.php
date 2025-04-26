<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acalento | Início</title>
    <link rel="stylesheet" href="css/header.css">
</head>
<body>
<header class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid d-flex align-items-center justify-content-between">

        <!-- Logo + Nome -->
        <div class="d-flex align-items-center gap-3">
            <img src="assets/logo/acalento-logo.svg" alt="Logo" width="100" height="100">
            <div class="header-titles d-flex flex-column">
                <h1 class="mb-0">Projeto</h1>
                <h1 class="mb-0">Acalento</h1>
            </div>
        </div>

        <!-- Navegação -->
        <nav class="header-nav">
            <ul class="navbar-nav flex-row gap-4">
                <li class="nav-item"><a class="nav-link" href="#">Nossa missão</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Blog Acalento</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Nos apoie!</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Portal Transparência</a></li>
            </ul>
        </nav>

        <!-- Botões -->
        <div class="header-login d-flex align-items-center gap-2">
            <?php if (isset($_SESSION['USER_NAME'])): ?>
                <span class="fw-bold text-primary"><?=$_SESSION['USER_NAME']?></span>
                <a href="index.php?common=3" class="btn btn-danger">Sair</a>
            <?php else: ?>
                <a href="index.php?common=2" class="btn btn-outline-primary">Entrar</a>
                <a href="index.php?common=5" class="btn btn-outline-primary">Cadastrar-se</a>
            <?php endif; ?>
        </div>

    </div>
</header>
</body>
</html>