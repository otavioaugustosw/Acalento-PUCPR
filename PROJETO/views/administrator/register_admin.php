<?php
include_once  (ROOT . "/php/config/database_php.php");
include_once (ROOT . "/php/auth_services/auth_service_php.php");
include_once (ROOT . "/php/handlers/form_validator_php.php");
include_once(ROOT .  "/components/sidebars/sidebars.php");
include_once(ROOT .  "/models/admin_models_php.php");
include_once(ROOT .  "/models/common_models_php.php");

$conn = connectDatabase();
?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="css/default.css">
        <link rel="stylesheet" href="css/sidebar.css">
        <link rel="stylesheet" href="css/form-style.css">
        <link rel="stylesheet" href="css/main-content.css">
        <title>Cadastro do administrador</title>
    </head>
    <body>

    <!-- monta a sidebar mobile -->
    <?php make_mobile_sidebar() ?>
    <div class="d-flex flex-nowrap">
        <!--    monta a sidebar desktop-->
        <?php make_sidebar(); ?>
    <div class="flex-grow-1 p-4 main-content">
        <main class="container-fluid align-content-center">
            <h2 class="form-title">Informações de cadastro</h2>
            <form method="POST" action="index.php?adm=11">

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

function validateAddress() {
    if (!is_numeric_only(preg_replace('/\D/', '', $_POST['cep'])) || !has_max_length(preg_replace('/\D/', '', $_POST['cep']), 8)) {
        display_validation('cep' , false);
        return false;
    }

    if (!is_alpha_only($_POST['rua']) || !has_max_length($_POST['rua'], 50)) {
        display_validation('rua', false);
        return false;
    }

    if (!is_numeric_only($_POST['numero']) || !has_max_length($_POST['numero'], 50)) {
        display_validation('numero', false);
        return false;
    }

    if (!is_alpha_only($_POST['bairro']) || !has_max_length($_POST['bairro'], 50)) {
        display_validation('bairro', false);
        return false;
    }

    if (!is_alpha_only($_POST['cidade']) || !has_max_length($_POST['cidade'], 50)) {
        display_validation('cidade', false);
        return false;
    }

    if (!is_alpha_only($_POST['estado']) || !has_max_length($_POST['estado'], 50)) {
        display_validation('estado', false);
        return false;
    }
    return true;
}

function validateUser()
{
    if (!is_full_name($_POST['nome']) || !has_max_length($_POST['nome'], 50)) {
        display_validation('nome', false);
        return false;
    }

    if (!is_cpf_valid($_POST['cpf'])) {
        display_validation('cpf' , false);
        return false;
    }

    if (!is_numeric_only(preg_replace('/\D/', '', $_POST['telefone']))) {
        display_validation('telefone', false);
        return false;
    }

    if (!is_valid_email($_POST['email'])) {
        display_validation('email', false);
        return false;
    }


    if (!is_date_valid($_POST['nascimento'])) {
        display_validation('nascimento', false);
        return false;
    }

    if (!has_min_length($_POST['senha'], 8) || !($_POST['senha'] === $_POST['confirmarSenha']) ) {
        display_validation('senha', false);
        display_validation('confirmarSenha', false);
        return false;
    }

    return true;
}

/**
 * Controla todo o fluxo de cadastro do usuário, incluindo validações,
 * verificação de existência e persistência no banco de dados.
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
 * @return void
 */
function submit_user(mysqli $conn): void
{
    if (!validate_user() || !validate_address()) {
        return;
    }

    $cpf = preg_replace('/\D/', '', $_POST['cpf']);
    $email = $_POST['email'];

    if (verify_user_existence($conn, $email, $cpf)) {
        return;
    }

    try {
        $address_id = create_address($conn, $_POST);
        create_user_admin($conn, $_POST, $address_id);
        ?> <script> window.location.href = 'index.php?common=6' </script> <?php
    } catch (Exception $e) {
        showError(15);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    submit_user($conn);
}
?>