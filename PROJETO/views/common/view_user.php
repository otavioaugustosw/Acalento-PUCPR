<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . "/php/handlers/form_validator_php.php");
include_once (ROOT . "/components/sidebars/sidebars.php");
include_once (ROOT . "/php/auth_services/auth_service_php.php");
include_once (ROOT . "/components/modal/modal.php");
include_once (ROOT . "/components/buttons/buttons.php");
include_once (ROOT . "/models/common_models_php.php");
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/form-style.css">
    <link rel="stylesheet" href="css/main-content.css">



    <title>Meus Dados</title>
</head>
<body>
<?php
if (isset($_GET['error'])) {
    showError($_GET['error']);
}

    if (isset($_GET['success'])) {
        showSucess($_GET['success']);
    }
    ?>
    <?php make_mobile_sidebar() ?>
    <div class="d-flex flex-nowrap">
        <!--    monta a sidebar desktop-->
        <?php make_sidebar(); ?>
    </div>

    <div class="flex-grow-1 p-4 main-content">
        <main class="container-fluid align-content-center">
            <h2 class="my-4">Meus Dados</h2>


                <!-- Dados Pessoais -->
                <div class="mb-4">
                    <h5 class="mb-3">Informações Pessoais</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nome Completo</label>
                            <div class="form-control"><?= $user->nome ?? 'Não informado' ?></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <div class="form-control "><?= $user->email ?? 'Não informado' ?></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">CPF</label>
                            <div class="form-control "><?= formatCPF($user->cpf) ?? 'Não informado' ?></div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Telefone</label>
                            <div class="form-control "><?= formatPhoneNumber($user->telefone) ?? 'Não informado' ?></div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Data de Nascimento</label>
                            <div class="form-control">
                                <?= !empty($user->nascimento) ? date('d/m/Y', strtotime($user->nascimento))
                                    : 'Não informado' ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Endereço -->
                <div class="mb-4">
                    <h5 class="mb-3">Endereço</h5>

                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label class="form-label">CEP</label>
                            <div class="form-control "><?= $user->cep ?? 'Não informado' ?></div>
                        </div>

                        <div class="col-md-5 mb-3">
                            <label class="form-label">Logradouro</label>
                            <div class="form-control "><?= $user->rua ?? 'Não informado' ?></div>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">Número</label>
                            <div class="form-control "><?= $user->numero ?? 'Não informado' ?></div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Complemento</label>
                            <div class="form-control"><?= !empty($user->complemento) ? $user->complemento : 'Não informado' ?>
                            </div>
                        </div>
                    </div>


                    <div class="row align-items-center">

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Bairro</label>
                            <div class="form-control "><?= $user->bairro ?? 'Não informado'?></div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Cidade</label>
                            <div class="form-control "><?= $user->cidade ?? 'Não informado' ?></div>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">Estado</label>
                            <div class="form-control "><?= $user->estado ?? 'Não informado' ?></div>
                        </div>

                        <?php makeFormButton("index.php?common=8","Editar Dados","","Editar Dados","btn btn-primary")?>
                        <?php
                        $modal_inputs = function () { ?>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control rounded-3" id="floatingInput" placeholder="********" name="password">
                            <label for="floatingInput">Nova senha</label>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control rounded-3" id="floatingPassword" placeholder="********" name="passwordConfirm">
                            <label for="floatingPassword">Confirmar nova senha</label>
                    <?php };
                    make_form_modal(
                        button_text: "Alterar senha",
                        modal_title: "Alterar senha",
                        form_action: "index.php?common=7",
                        modal_inputs: $modal_inputs,
                    );
                    make_default_modal(
                        $_SESSION['USER_ID'],
                        button_text: 'Inativar conta',
                        modal_title: 'Confirmar inativação',
                        modal_body: 'Tem certeza que deseja inativar sua conta?',
                        confirm_text: 'Sim, inativar',
                        form_action: "index.php?common=9",
                        hide_id: true
                    );
                    ?>
                </div>
            </div>
        </main>
    </div>
    </body>
    </html>

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    var_dump($_POST);
    update_password($conn, $_SESSION['USER_ID']);
}

function update_password($conn, $id_usuario) {
        if (!hasMinLength($_POST['password'], 8) || ($_POST['password'] !== $_POST['passwordConfirm'])) {
            displayValidation('password', false);
            displayValidation('passwordConfirm', false);
            showError(17);
            return false;
        } else {
            $senha = generate_password_hash($_POST['password']);

            try {
                $query = "UPDATE usuario SET senha = ? WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("si", $senha, $id_usuario);
                if ($stmt->execute()) {
                    showSucess(10);
                    return true;
                }
            } catch (Exception $e) {
                showError(15);
                return false;
            }
        }
}