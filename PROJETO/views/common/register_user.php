<?php
include (ROOT . "/php/config/database_php.php");
include(ROOT . "/php/auth_services/auth_service_php.php");
include(ROOT . "/php/handlers/form_validator_php.php");
$conn = connectDatabase();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/form-style.css">
    <link rel="stylesheet" href="css/main-content.css">
    <title>Cadastro de usuário</title>
</head>
<body>

<?php include ROOT . "/components/header/header.php"; ?>

<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg p-4">
                <h2 class="form-title">Informações pessoais</h2>
    <form method="POST" action="index.php?common=5">
        <!-- Nome -->
        <div class="mb-3">
            <label for="nome" class="form-label">Nome completo *</label>
            <input type="text" class="form-control " id="nome" name="nome" maxlength="50" value="<?= $_POST['nome'] ?? ''?>">
        </div>

        <div class="mb-3" id="cpf-field">
            <label for="cpf" class="form-label">CPF*</label>
            <input type="text" class="form-control " id="cpf" name="cpf" maxlength="14" value="<?= $_POST['cpf'] ?? ''?>">
        </div>
        <!-- CPF -->
        <div class="row">

            <div class="col-md-6 mb-3">
                <label for="nascimento" class="form-label">Data de nascimento *</label>
                <input type="date" class="form-control " id="nascimento" name="nascimento" value="<?= $_POST['nascimento'] ?? ''?>">
            </div>

            <div class="col-md-6 mb-3">
                <label for="telefone" class="form-label">Telefone *</label>
                <input type="text" class="form-control" id="telefone" name="telefone"  maxlength="15" value="<?= $_POST['telefone'] ?? ''?>">
            </div>
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">E-mail *</label>
            <input type="email" class="form-control" id="email" name="email" maxlength="50" value="<?= $_POST['email'] ?? ''?>">
        </div>

        <!-- Senha -->
        <div class="mb-3">
            <label for="senha" class="form-label">Senha *</label>
            <input type="password" class="form-control " id="senha" name="senha" maxlength="256">
        </div>

        <!-- Confirmar Senha -->
        <div class="mb-3">
            <label for="confirmarSenha" class="form-label">Confirmar Senha *</label>
            <input type="password" class="form-control " id="confirmarSenha" name="confirmarSenha" maxlength="256">
        </div>
        <!-- CEP -->
        <div class="mb-3 mt-2">
            <label for="cep" class="form-label">CEP *</label>
            <input type="text" class="form-control" id="cep" name="cep" maxlength="9" pattern="\d{5}-\d{3}" value="<?= $_POST['cep'] ?? '' ?>">
        </div>

        <!-- rua -->
        <div class="mb-3">
            <label for="rua" class="form-label">Logradouro *</label>
            <input type="text" class="form-control " id="rua" name="rua" maxlength="50" value="<?= $_POST['rua'] ?? '' ?>">
        </div>

        <!-- bairro -->
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="bairro" class="form-label">Bairro *</label>
                <input type="text" class="form-control " id="bairro" name="bairro" maxlength="50" value="<?= $_POST['bairro'] ?? '' ?>">
            </div>

            <!-- Número -->
            <div class="col-md-4 mb-3">
                <label for="numero" class="form-label">Número *</label>
                <input type="number" class="form-control " id="numero" name="numero" maxlength="6" value="<?= $_POST['numero'] ?? ''?>">
            </div>

            <!-- Complemento -->
            <div class="col-md-4 mb-3">
                <label for="complemento" class="form-label">Complemento </label>
                <input type="text" class="form-control " id="complemento" name="complemento" maxlength="50" value="<?= $_POST['complemento'] ?? ''?>">
            </div>
        </div>

        <!-- Cidade -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="cidade" class="form-label">Cidade *</label>
                <input type="text" class="form-control " id="cidade" name="cidade" maxlength="50" value="<?= $_POST['cidade'] ?? '' ?>">
            </div>

            <!-- Estado-->
            <div class="col-md-6 mb-3">
                <label for="estado" class="form-label">Estado *</label>
                <input type="text" class="form-control " id="estado" name="estado" maxlength="50" value="<?= $_POST['estado'] ?? '' ?>">
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-primary">Finalizar Cadastro</button>
        </div>
    </form>
            </div>
        </div>
    </div>
</main>
</div>
</body>
<script src="assets/js/confirmation.js" defer></script>
</html>

<?php

function validateAddress() {
    if (!isNumericOnly(preg_replace('/\D/', '', $_POST['cep'])) || !hasMaxLength(preg_replace('/\D/', '', $_POST['cep']), 8)) {
        displayValidation('cep' , false);
        return false;
    }

    if (!isAlphaOnly($_POST['rua']) || !hasMaxLength($_POST['rua'], 50)) {
        displayValidation('rua', false);
        return false;
    }

    if (!isNumericOnly($_POST['numero']) || !hasMaxLength($_POST['numero'], 50)) {
        displayValidation('numero', false);
        return false;
    }

    if (!isAlphaOnly($_POST['bairro']) || !hasMaxLength($_POST['bairro'], 50)) {
        displayValidation('bairro', false);
        return false;
    }

    if (!isAlphaOnly($_POST['cidade']) || !hasMaxLength($_POST['cidade'], 50)) {
        displayValidation('cidade', false);
        return false;
    }

    if (!isAlphaOnly($_POST['estado']) || !hasMaxLength($_POST['estado'], 50)) {
        displayValidation('estado', false);
        return false;
    }
    return true;
}

function validateUser()
{
    if (!isFullName($_POST['nome']) || !hasMaxLength($_POST['nome'], 50)) {
        displayValidation('nome', false);
        return false;
    }

    if (!isCPFValid($_POST['cpf'])) {
        displayValidation('cpf' , false);
        return false;
    }

    if (!isNumericOnly(preg_replace('/\D/', '', $_POST['telefone']))) {
        displayValidation('telefone', false);
        return false;
    }

    if (!isValidEmail($_POST['email'])) {
        displayValidation('email', false);
        return false;
    }


    if (!isDateValid($_POST['nascimento'])) {
        displayValidation('nascimento', false);
        return false;
    }

    if (!hasMinLength($_POST['senha'], 8) || !($_POST['senha'] === $_POST['confirmarSenha']) ) {
        displayValidation('senha', false);
        displayValidation('confirmarSenha', false);
        return false;
    }

    return true;
}

function verifyUserExistence($conn, $email, $cpf)
{
    try {
        // Verifica se já existe cadastro com o Email
        $query = "SELECT email FROM usuario WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultEmail = $stmt->get_result();

        if ($resultEmail->num_rows > 0) {
            showError(16);
            exit();
        }

        // Verifica se já existe cadastro com o CPF
        $query = "SELECT cpf FROM usuario WHERE cpf = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $cpf);
        $stmt->execute();
        $resultCpf = $stmt->get_result();

        if ($resultCpf->num_rows > 0) {
            showError(19);
            exit();
        }

        // Não encontrou nem email nem cpf já cadastrados
        return false;

    } catch (Exception $e) {
        showError(15);
        exit();
    }
}


function submitUser($conn)
{
    $cep = preg_replace('/\D/', '', $_POST['cep']);
    $rua = $_POST['rua'];
    $numero = $_POST['numero'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $complemento = $_POST['complemento'];

    try {
        // cria o novo endereço
        $query = "INSERT INTO endereco (cep, rua, numero, bairro, cidade, estado, complemento) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssissss", $cep, $rua, $numero, $bairro, $cidade, $estado, $complemento);
        $stmt->execute();
    } catch (Exception $e) {
        showError(15);
        exit();
    }

    $id_endereco = $conn->insert_id;
    $email = $_POST['email'];
    $senha = generate_password_hash($_POST['senha']);
    $nome = ucwords(strtolower($_POST['nome']));
    $cpf = preg_replace('/\D/', '', $_POST['cpf']);
    $telefone = preg_replace('/\D/', '', $_POST['telefone']);
    $nascimento = $_POST['nascimento'];

    try {
        // cria o novo endereço
        $query = "INSERT INTO usuario(id_endereco, email, senha, nome, cpf, telefone, nascimento)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("issssss", $id_endereco, $email, $senha, $nome, $cpf, $telefone, $nascimento);
        $stmt->execute();
    } catch (Exception $e) {
        showError(15);
        exit();
    }

}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (validateUser() && validateAddress()) {
        $cpf = preg_replace('/\D/', '', $_POST['cpf']);
        $email = $_POST['email'];
        if (!verifyUserExistence($conn, $email, $cpf)) {
            submitUser($conn);
            ?>
            <script>
                window.location.href = "index.php?common=2";
            </script>
            <?php
        }
    }
}
?>