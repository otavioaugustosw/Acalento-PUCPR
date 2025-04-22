<?php
global $obj;
error_reporting(E_ALL);
ini_set('display_errors', 1);
include (ROOT . "/php/config/database_php.php");
include (ROOT . "/php/auth_services/AuthService.php");
$conexao = connectDatabase();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    //Diferentes tipos de usuário
    $tipos = $_POST['tipo_usuario'] ?? [];
    $eh_doador = in_array('pj', $tipos) ? 1 : 0;
    $eh_doador = in_array('pf', $tipos) ? 1 : 0;
    $eh_voluntario = in_array('voluntario', $tipos) ? 1 : 0;
    $eh_adm = 0;

    //Pega os dados
    $email = $_POST['email'];
    $senha= AuthService::generatePasswordHash('senha');
    $nome = $_POST['nome'];
    $cpf = isset($_POST['cpf']) ? preg_replace('/\D/', '', $_POST['cpf']) : null;
    $cnpj = isset($_POST['cnpj']) ? preg_replace('/\D/', '', $_POST['cnpj']) : null;
    $telefone = isset($_POST['telefone']) ? preg_replace('/\D/', '', $_POST['telefone']) : null;
    $nascimento = $_POST['nascimento'];

    $query = "
    INSERT INTO usuario(email, senha, nome, cpf, cnpj, telefone, nascimento, 
        eh_doador, eh_voluntario
    ) VALUES (
        '$email',
        '$senha',
        '$nome',
        " . ($cpf ? "'$cpf'" : "NULL") . ",
        " . ($cnpj ? "'$cnpj'" : "NULL") . ",
        '$telefone',
        '$nascimento',
        '$eh_doador',
        '$eh_voluntario'
    )
";

    $resultado = $obj->query($query);

    if (!$resultado) {
        echo "<span class='alert alert-danger'>Não funcionou! Erro: " . $obj->error . "</span>";
    } else {
        // Pega o ID do usuário recém-criado
        $id_usuario = $obj->insert_id;
        // Redireciona com o ID do usuário
        header('Location: register_address.php?id_usuario=' . $id_usuario);
        exit();
        }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/form-style.css">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/main-content.css">

    <title>Cadastro de usuário</title>

</head>
<body>

<header>
    <div class="container">
        <div class="logo">
            <img src="assets/logo/acalento-logo.svg" alt="logo acalento">
            <h1>Projeto Acalento</h1>
        </div>
        <nav>
            <ul>
                <li><a href="#">nossa missão</a></li>
                <li><a href="#">blog acalento</a></li>
                <li><a href="#">nos apoie!</a></li>
                <li><a href="#">portal transparência</a></li>
                <a href="" class=" btn btn-primary m-3">Cadastre-se</a>
                <a href="" class=" btn btn-secondary">Login</a>
            </ul>
        </nav>
    </div>
</header>


<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg p-4">
                <h2 class="form-title">Informações pessoais</h2>

    <form method="POST" action="views/common/registerAddress.php">
        <!-- Nome -->
        <div class="mb-3">
            <label for="nome" class="form-label">Nome completo *</label>
            <input type="text" class="form-control border-dark-subtle" id="nome" name="nome" required maxlength="50">
        </div>
        <!-- Tipo de usuario check -->
        <div class="mb-3">
            <label class="form-label">Tipo de usuário*:</label>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="pf" id="pf" name="tipo_usuario[]">
                <label class="form-check-label" for="pf">Doador PF</label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="pj" id="pj" name="tipo_usuario[]">
                <label class="form-check-label" for="pj">Doador PJ</label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="voluntario" id="voluntario" name="tipo_usuario[]">
                <label class="form-check-label" for="voluntario">Voluntário</label>
            </div>
        </div>

        <!-- CPF | CNPJ -->
        <div class="row">
            <div class="col-md-6 mb-3 d-none" id="cpf-field">
                <label for="cpf" class="form-label">CPF*</label>
                <input type="text" class="form-control border-dark-subtle" id="cpf" name="cpf" maxlength="14">
            </div>

            <div class="col-md-6 mb-3 d-none" id="cnpj-field">
                <label for="cnpj" class="form-label">CNPJ*</label>
                <input type="text" class="form-control border-dark-subtle" id="cnpj" name="cnpj" maxlength="18">
            </div>


            <div class="col-md-6 mb-3">
                <label for="nascimento" class="form-label">Data de nascimento *</label>
                <input type="date" class="form-control border-dark-subtle" id="nascimento" name="nascimento" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="telefone" class="form-label">Telefone *</label>
                <input type="text" class="form-control border-dark-subtle" id="telefone" name="telefone" required maxlength="15">
            </div>
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">E-mail *</label>
            <input type="email" class="form-control border-dark-subtle" id="email" name="email" required maxlength="50">
        </div>

        <!-- Senha -->
        <div class="mb-3">
            <label for="senha" class="form-label">Senha *</label>
            <input type="password" class="form-control border-dark-subtle" id="senha" name="senha" required maxlength="256">
        </div>

        <!-- Confirmar Senha -->
        <div class="mb-3">
            <label for="confirmarSenha" class="form-label">Confirmar Senha *</label>
            <input type="password" class="form-control border-dark-subtle" id="confirmarSenha" name="confirmarSenha" required maxlength="256">
        </div>

        <button type="submit" class="btn btn-primary">Continuar</button>

    </form>
            </div>
        </div>
    </div>
</main>

</div>

</body>

<script src="assets/js/confirmation.js" defer></script>


</html>