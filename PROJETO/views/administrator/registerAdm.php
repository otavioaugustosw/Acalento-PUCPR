<?php
include (ROOT . "/php/config/database_php.php");
include (ROOT . "/php/auth_services/AuthService.php");
include (ROOT . "/php/handlers/formValidator.php");
$obj = connectDatabase();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/sidebars.css">
    <link rel="stylesheet" href="css/form-style.css">
    <link rel="stylesheet" href="css/main-content.css">



    <title>Cadastro do administrador</title>
</head>
<body>

<?php include(ROOT . "/components/sidebars/sidebar-mobile.php") ?>
<div class="d-flex flex-nowrap">
    <?php include(ROOT .  "/components/sidebars/sidebars.php") ?>
</div>
<div class="flex-grow-1 p-4 main-content">
    <main class="container-fluid align-content-center">
        <h2 class="form-title">Informações de cadastro</h2>
        <form method="POST" action="index.php?adm=12">

            <div class="row">
                <!-- Nome -->
                <div class="col-md-6 mb-3">
                    <label for="nome" class="form-label">Nome completo *</label>
                    <input type="text" class="form-control " id="nome" name="nome" maxlength="50" value="<?= $_POST['nome'] ?? ''?>">
                </div>
                <!-- Email -->
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">E-mail *</label>
                    <input type="email" class="form-control" id="email" name="email" maxlength="50" value="<?= $_POST['email'] ?? ''?>">
                </div>
            </div>

                    <div class="row">
                        <!-- Tipo de Usuário, porque o adms só são criados por adms, mas estes podem cadastrar pessoas comuns -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo de usuário *</label>
                            <div class="row">
                                <div class="col-auto">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipo_usuario" id="tipo_comum" value="0" <?= (isset($_POST['tipo_usuario']) && $_POST['tipo_usuario'] == '0') ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="tipo_comum">Comum</label>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipo_usuario" id="tipo_admin" value="1" <?= (isset($_POST['tipo_usuario']) && $_POST['tipo_usuario'] == '1') ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="tipo_admin">Administrador</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

            <div class="row">
                <!-- Data de nascimento -->
                <div class="col-md-4 mb-3">
                    <label for="nascimento" class="form-label">Data de nascimento *</label>
                    <input type="date" class="form-control " id="nascimento" name="nascimento" value="<?= $_POST['nascimento'] ?? ''?>">
                </div>
                <!-- Telefone -->
                <div class="col-md-4 mb-3">
                    <label for="telefone" class="form-label">Telefone *</label>
                    <input type="text" class="form-control" id="telefone" name="telefone"  maxlength="15" value="<?= $_POST['telefone'] ?? ''?>">
                </div>
                <!-- CPF -->
                <div class="col-md-4 mb-3" id="cpf-field">
                    <label for="cpf" class="form-label">CPF*</label>
                    <input type="text" class="form-control " id="cpf" name="cpf" maxlength="14" value="<?= $_POST['cpf'] ?? ''?>">
                </div>
            </div>


            <div class="row">
                <!-- Senha -->
                <div class="col-md-6 mb-3">
                    <label for="senha" class="form-label">Senha *</label>
                    <input type="password" class="form-control " id="senha" name="senha" maxlength="256">
                </div>

                <!-- Confirmar Senha -->
                <div class="col-md-6 mb-3">
                    <label for="confirmarSenha" class="form-label">Confirmar Senha *</label>
                    <input type="password" class="form-control " id="confirmarSenha" name="confirmarSenha" maxlength="256">
                </div>
            </div>

            <div class="row">
                <!-- CEP -->
                <div class="col-md-6 mb-3">
                    <label for="cep" class="form-label">CEP *</label>
                    <input type="text" class="form-control" id="cep" name="cep" maxlength="9" pattern="\d{5}-\d{3}" value="<?= $_POST['cep'] ?? '' ?>">
                </div>

                <!-- rua -->
                <div class="col-md-6 mb-3">
                    <label for="rua" class="form-label">Logradouro *</label>
                    <input type="text" class="form-control " id="rua" name="rua" maxlength="50" value="<?= $_POST['rua'] ?? '' ?>">
                </div>
            </div>

                <!-- bairro -->
                <div class="row">
                    <!-- bairro -->
                    <div class="col-md-6 mb-3">
                        <label for="bairro" class="form-label">Bairro *</label>
                        <input type="text" class="form-control " id="bairro" name="bairro" maxlength="50" value="<?= $_POST['bairro'] ?? '' ?>">
                    </div>

                    <!-- Número -->
                    <div class="col-md-6 mb-3">
                        <label for="numero" class="form-label">Número *</label>
                        <input type="number" class="form-control " id="numero" name="numero" maxlength="6" value="<?= $_POST['numero'] ?? ''?>">
                    </div>
                </div>



            <div class="row">
                <!-- Cidade -->
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

            <div class="row"></div>

                <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary largura-50 mt-4">Finalizar Cadastro</button>
                </div>
            </div>
    </form>
    </main>
</div>

</body>
<script src="assets/js/confirmation.js" defer></script>
</html>

<?php

function validateAddress($sql) {
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

function validateUser($sql)
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

function submitUser($sql)
{
    $cep = preg_replace('/\D/', '', $_POST['cep']);
    $rua = $_POST['rua'];
    $numero = $_POST['numero'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];

    try {
        // cria o novo endereço
        $query = "INSERT INTO endereco (cep, rua, numero, bairro, cidade, estado) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $sql->prepare($query);
        $stmt->bind_param("ssisss", $cep, $rua, $numero, $bairro, $cidade, $estado);
        $stmt->execute();
    } catch (Exception $e) {
        showError(15);
        exit();
    }

    $id_endereco = $sql->insert_id;
    $email = $_POST['email'];
    $senha = AuthService::generatePasswordHash($_POST['senha']);
    $nome = $_POST['nome'];
    $cpf = preg_replace('/\D/', '', $_POST['cpf']);
    $telefone = preg_replace('/\D/', '', $_POST['telefone']);
    $nascimento = $_POST['nascimento'];
    $eh_adm = $_POST['tipo_usuario'];

    try {
        // cria o novo endereço
        $query = "INSERT INTO usuario(id_endereco, email, senha, nome, cpf, telefone, nascimento, eh_adm)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $sql->prepare($query);
        $stmt->bind_param("issssssi", $id_endereco, $email, $senha, $nome, $cpf, $telefone, $nascimento, $eh_adm);
        $stmt->execute();
    } catch (Exception $e) {
        showError(15);
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (validateUser($obj) && validateAddress($obj)) {
        submitUser($obj);
        ?>
        <script>
            window.location.href = "index.php?common=2"
        </script>
        <?php
    }
}
