<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . '/php/handlers/form_validator_php.php');
include_once (ROOT .  "/components/sidebars/sidebars.php");
include_once (ROOT .  "/models/common_models_php.php");
include_once (ROOT . "/models/admin_models_php.php");
include_once (ROOT .  "/components/back/back.php");

// conexão com o banco de dados
$conn = connectDatabase();
$punishment_id = $_GET['id'] ?? 7;

$punishment = get_all_punishments($conn, " WHERE up.id = $punishment_id");

if (!$punishment) {
    showError(7);
} else {
    $punishment = $punishment->fetch_object();
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
                <?php make_buttom_back("index.php?common=11");?>
                <div class="mb-3">
                    <!-- aqui vai o que você quer por -->
                    <h4>Justificar penalidade</h4>
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
                        <form action="index.php?common=17&id=<?= $punishment_id ?>" method="post">
                            <?php
                            if ($punishment->revisado) {
                                ?>
                                <div class="col-md-12 row-cols-6 mb-3">
                                    <label for="inputDescricao" class="form-label">Justificativa de <?= preg_split('/[ ]/', $punishment->usuario_nome ?? "Desconhecido" )[0] ?></label>
                                    <div class="form-control"><?= $punishment->justificativa ?? "Usuário ainda não se justificou"?></div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="col-12">
                                    <label for="inputJustification" class="form-label">Justificativa*></label>
                                    <textarea type="text" class="form-control" id="inputJustification" name="justification" rows="5"><?= $_POST['justification'] ?? null ?></textarea>
                                    <div id="inputJustification" class="invalid-feedback">
                                        Digite uma justificativa válida.
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <button type="submit" class="btn btn-primary">Justificar</button>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </form>
                    </div>
                </div>
        </main>
    </div>
</div>
</body>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // justifica somente se não foi revisado pelo admin ainda
    if (!$punishment->revisado) {
        submitInformation($conn, $punishment_id);
    }
}

function submitInformation($conn, $punishment_id) {
    if (!is_alpha_only($_POST['justification']) || !has_max_length($_POST['justification'], 100)) {
        display_validation('inputJustification', false);
        return;
    }

    $did_justified_punishment = update_punishment_justification($conn, $punishment_id, $_POST['justification'], $_SESSION['USER_ID']);

    if ($did_justified_punishment) {
        showSucess(362);
    }
    else {
        showError(7);
    }
}