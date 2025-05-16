<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . '/php/handlers/form_validator_php.php');
include_once (ROOT .  "/components/sidebars/sidebars.php");
include_once (ROOT .  "/models/common_models_php.php");
include_once (ROOT . "/models/admin_models_php.php");
include_once (ROOT .  "/components/back/back.php");
include_once (ROOT .  "/components/modal/modal.php");

// conexão com o banco de dados
$conn = connectDatabase();
$punishment_id = $_GET['id'] ?? 7;

// usuario não pode retirar ou confirmar sua propria punição mesmo sendo admin
$punishment = get_all_punishments($conn, " WHERE up.id = $punishment_id AND up.id_usuario !=" . $_SESSION['USER_ID']);

if (!$punishment) {
    showError(7);
} else {
    $punishment = $punishment->fetch_object();
}
if (isset($_GET['confirmar'])) {
    if ($_GET['confirmar'] == 1) {
        $did_confirm_punishment = toggle_user_punishment_status($conn, $punishment_id, false);
        $did_confirm_punishment ? showSucess(360) : showError(360);
    }
    if ($_GET['confirmar'] == 0) {
        $did_cancel_punishment = toggle_user_punishment_status($conn, $punishment_id, true);
        $did_cancel_punishment ? showSucess(361) : showError(360);
    }
}

?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
        <link rel="stylesheet" href="css/form-style.css">
        <link rel="stylesheet" href="css/default.css">
        <link rel="stylesheet" href="css/sidebar.css">
        <link rel="stylesheet" href="css/main-content.css">
        <title>Acalento | Criar evento</title>
    </head>

    <body>
    <!-- monta a sidebar mobile -->
    <?php make_mobile_sidebar() ?>
    <div class="d-flex flex-nowrap">
        <!--    monta a sidebar desktop-->
        <?php make_sidebar(); ?>
        <!-- fim sidebar -->

        <!-- conteudo -->
        <div class="main-content">
            <main class="px-5 row align-items-center justify-content-center">
                <div class="container-fluid">
                    <div class="mb-3">
                        <?php make_buttom_back();?>
                        <!-- aqui vai o que você quer por -->
                        <h4>Verificar punição</h4>
                        <div class="div">

                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nome Completo</label>
                                <div class="form-control"><?= $punishment->usuario_nome ?? ""?></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <div class="form-control"><?= $punishment->usuario_email ?? ""?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Local da Ocorrência</label>
                                <div class="form-control"><?= $punishment->evento_nome ?? "Fora de evento"?></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Data</label>
                                <div class="form-control">
                                    <?= !empty($punishment->data_punicao) ? date('d/m/Y', strtotime($punishment->data_punicao)) : "N/A"
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Motivo da punição</label>
                                <div class="form-control"><?= $punishment->motivo ?? "N/A"?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 row-cols-6 mb-3">
                                <label for="inputDescricao" class="form-label">Justificativa de <?= preg_split('/[ ]/', $punishment->usuario_nome ?? "Desconhecido" )[0] ?></label>
                                <div class="form-control"><?= $punishment->justificativa ?? "Usuário ainda não se justificou"?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3 d-flex">
                                <?php
                                    makeModal(
                                            $punishment_id,
                                        button_classes: "btn btn-danger",
                                        button_text: "Retirar penalidade",
                                        modal_title: "Retirar penalidade",
                                        modal_body:"Deseja retirar a penalidade deste usuário?",
                                        confirm_text: "Sim, retirar",
                                        form_action: "index.php?adm=10&confirmar=0&id=$punishment_id"
                                    );
                                makeModal(
                                    $punishment_id. "confirm",
                                    button_classes: "btn btn-primary",
                                    button_text: "Confirmar penalidade",
                                    modal_title: "Confirmar penalidade",
                                    modal_body:"Deseja penalizar este usuário?",
                                    confirm_text: "Sim, penalizar",
                                    form_action: "index.php?adm=10&confirmar=1&id=$punishment_id"
                                );

                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    </body>
    </html>