<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . "/php/handlers/form_validator_php.php");
include_once (ROOT . "/components/sidebars/sidebars.php");
include_once (ROOT . "/models/common_models_php.php");
include_once (ROOT . "/components/buttons/buttons.php");


$conn = connectDatabase();
load_user_session_data($conn);
$user = get_user_data($conn, $_SESSION['USER_ID']);
if (!$user) {
    showError(7);
}
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
    <title>Meus Dados</title>
</head>
<body>
<?php make_mobile_sidebar() ?>
<div class="d-flex flex-nowrap">
    <!--    monta a sidebar desktop-->
    <?php make_sidebar(); ?>


    <div class="flex-grow-1 p-4 main-content">
        <main class="container-fluid align-content-center">
            <h2 class="my-4">Editar Meus Dados</h2>


            <form action="index.php?common=8" method="post">
            <!-- Dados Pessoais -->
                <div class="mb-4">
                    <h5 class="mb-3">Informações Pessoais</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" name="nome" id="nome"
                                   value="<?= $_POST['nome'] ?? $user->nome ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="email"
                                   value="<?= $_POST['email'] ?? $user->email ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">CPF</label>
                            <input type="text" class="form-control" name="cpf" id="cpf"
                                   value="<?= $_POST['cpf'] ?? formatCPF($user->cpf) ?>">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Telefone</label>
                            <input type="text" class="form-control" name="telefone" id="telefone"
                                   value="<?= $_POST['telefone'] ?? formatPhoneNumber($user->telefone) ?>">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Data de Nascimento</label>
                            <input type="date" class="form-control" name="nascimento" id="nascimento"
                                   value="<?= $_POST['nascimento'] ?? $user->nascimento ?>">
                        </div>
                    </div>
                </div>

            <!-- Endereço -->
                <div class="mb-4">
                <h5 class="mb-3">Endereço</h5>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">CEP</label>
                            <input type="text" class="form-control" name="cep" id="cep"
                                   value="<?= $_POST['cep'] ?? $user->cep ?>" maxlength="9">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Logradouro</label>
                            <input type="text" class="form-control" name="rua" id="rua"
                                   value="<?= $_POST['rua'] ?? $user->rua ?>" maxlength="50">
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">Número</label>
                            <input type="text" class="form-control" name="numero" id="numero"
                                   value="<?= $_POST['numero'] ?? $user->numero ?>" maxlength="6">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Complemento </label>
                            <input type="text" class="form-control" id="complemento" name="complemento"
                                   maxlength="50" value="<?= $_POST['complemento'] ?? $user->complemento ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Bairro</label>
                            <input type="text" class="form-control" name="bairro" id="bairro"
                                   value="<?= $_POST['bairro'] ?? $user->bairro ?>" maxlength="50">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Cidade</label>
                            <input type="text" class="form-control" name="cidade" id="cidade"
                                   value="<?= $_POST['cidade'] ?? $user->cidade ?>" maxlength="50">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Estado</label>
                            <input type="text" class="form-control" name="estado" id="estado"
                                   value="<?= $_POST['estado'] ?? $user->estado ?>" maxlength="50">
                        </div>
                        <div class="col-md-3 mt-5 mb-3">
                            <button type="submit" class="btn btn-primary largura-completa">Salvar alterações</button>
                        </div>
                    </div>

                </div>
                </div>
            </form>
        </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/confirmation.js"></script>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    validate_address();
    validate_user();
}

function validate_address(): bool
{
    if (!isset($_POST['cep']) ||
        !is_numeric_only(preg_replace('/\D/', '', $_POST['cep'])) ||
        !has_max_length(preg_replace('/\D/', '', $_POST['cep']), 8)) {
        display_validation('cep', false);
        return false;
    }

    if (!isset($_POST['rua']) || !is_alpha_only($_POST['rua']) || !has_max_length($_POST['rua'], 50)) {
        display_validation('rua', false);
        return false;
    }

    if (!isset($_POST['numero']) || !is_numeric_only($_POST['numero']) || !has_max_length($_POST['numero'], 50)) {
        display_validation('numero', false);
        return false;
    }

    if (!isset($_POST['bairro']) || !is_alpha_only($_POST['bairro']) || !has_max_length($_POST['bairro'], 50)) {
        display_validation('bairro', false);
        return false;
    }

    if (!isset($_POST['cidade']) || !is_alpha_only($_POST['cidade']) || !has_max_length($_POST['cidade'], 50)) {
        display_validation('cidade', false);
        return false;
    }

    if (!isset($_POST['estado']) || !is_alpha_only($_POST['estado']) || !has_max_length($_POST['estado'], 50)) {
        display_validation('estado', false);
        return false;
    }

    return true;
}


function validate_user(): bool
{
    if (!isset($_POST['nome']) || !is_full_name($_POST['nome']) || !has_max_length($_POST['nome'], 50)) {
        display_validation('nome', false);
        return false;
    }

    if (!isset($_POST['cpf']) || !is_cpf_valid($_POST['cpf'])) {
        display_validation('cpf', false);
        return false;
    }

    if (!isset($_POST['telefone']) || !is_numeric_only(preg_replace('/\D/', '', $_POST['telefone']))) {
        display_validation('telefone', false);
        return false;
    }

    if (!isset($_POST['email']) || !is_valid_email($_POST['email'])) {
        display_validation('email', false);
        return false;
    }

    if (!isset($_POST['nascimento']) || !is_date_valid($_POST['nascimento'])) {
        display_validation('nascimento', false);
        return false;
    }

    return true;
}


function submit_user(mysqli $conn, $address_id): void
{
    if (!validate_user() || !validate_address()) {
        return;
    }

    $cpf = preg_replace('/\D/', '', $_POST['cpf']);
    $email = $_POST['email'];

    if (verify_user_existence($conn, $email, $cpf, $_SESSION['USER_ID'])) {
        return;
    }

    try {
        update_user_and_address($conn, $_SESSION['USER_ID'], $address_id, $_POST);
        ?> <?php
    } catch (Exception $e) {
        showError(15);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    submit_user($conn, $user->id_endereco);
}
?>