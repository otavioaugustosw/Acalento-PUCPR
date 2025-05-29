<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . '/php/handlers/form_validator_php.php');
include_once (ROOT .  "/components/sidebars/sidebars.php");
include_once (ROOT .  "/models/voluntary_models_php.php");
include_once (ROOT .  "/models/admin_models_php.php");
include_once (ROOT .  "/models/common_models_php.php");
include_once (ROOT .  "/components/back/back.php");
include_once (ROOT .  "/components/buttons/buttons.php");

// conexão com o banco de dados
$conn = connectDatabase();
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
    <title>Acalento | Assentamento </title>
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
        <main class="px-5 row justify-content-center">
            <div class="container-fluid">
                <div class="mb-3">
                    <!-- aqui vai o que você quer por -->
                    <div class="mb-5">
                        <?php make_buttom_back("index.php?adm=8");?>
                    </div>
                    <h4>Assentamento</h4>
                    <form class="row g-3" method="POST" action="index.php?adm=4" enctype="multipart/form-data">
                        <div class="col-md-6">
                            <label for="nome" class="form-label">Nome*</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?= $_POST['nome'] ?? null ?>">
                            <div id="validacaoNome" class="invalid-feedback">
                                Digite um nome de assentamento válido.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="familias" class="form-label">Quantidade de famílias*</label>
                            <input type="number" class="form-control" id="familias" name="familias" value="<?= $_POST['familias'] ?? null ?>">
                            <div id="validacaoFamilias" class="invalid-feedback">
                                Digite um número.
                            </div>
                        </div>

                        <h5 class="mb-3">Endereço</h5>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">CEP *</label>
                                <input type="text" class="form-control" id="cep" name="cep" maxlength="9" pattern="\d{5}-\d{3}" value="<?= $_POST['cep'] ?? '' ?>">
                                <div id="validacaoCep" class="invalid-feedback">
                                    Digite um CEP válido.
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="rua" class="form-label">Logradouro</label>
                                <input type="text" class="form-control" id="rua" name="rua" value="<?= $_POST['rua'] ?? null ?>">
                                <div id="validacaoRua" class="invalid-feedback">
                                    Digite uma rua válida.
                                </div>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label for="numero" class="form-label">Número</label>
                                <input type="number" class="form-control" id="numero" name="numero" value="<?= $_POST['numero'] ?? null ?>">
                                <div id="validacaoNumero" class="invalid-feedback">
                                    Digite um número.
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="complemento" class="form-label">Complemento</label>
                                <input type="text" class="form-control" id="complemento" name="complemento" value="<?= $_POST['complemento'] ?? null ?>">
                                <div id="validacaoComplemento" class="invalid-feedback">
                                    Digite um complemento válido.
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="bairro" class="form-label">Bairro</label>
                                <input type="text" class="form-control" id="bairro" name="bairro" value="<?= $_POST['bairro'] ?? null ?>">
                                <div id="validacaoBairro" class="invalid-feedback">
                                    Digite um bairro válido.
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="cidade" class="form-label">Cidade</label>
                                <input type="text" class="form-control" id="cidade" name="cidade" value="<?= $_POST['cidade'] ?? null ?>">
                                <div id="validacaoCidade" class="invalid-feedback">
                                    Digite uma cidade válida.
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <input type="text" class="form-control" id="estado" name="estado" value="<?= $_POST['estado'] ?? null ?>">
                                <div id="validacaoEstado" class="invalid-feedback">
                                    Digite um estado válido.
                                </div>
                            </div>
                            <div class="col-md-3 mt-5 mb-3">
                                <?php makeFormButton("submit", "salvar assentamento", "", "Salvar assentamento"); ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    </div>
    </main>
</div>
</div>
</body>
<script src="assets/js/confirmation.js" defer></script>
</html>
<?php

function submitInformation()
{
    // Validação dos campos
    if (!is_alpha_only($_POST['nome']) || !has_max_length($_POST['nome'], 50)) {
        display_validation('nome', false);
        return;
    }

    if (!is_numeric_only($_POST['familias'])) {
        display_validation('familias', false);
        return;
    }

    if (!is_numeric_only(preg_replace('/\D/', '', $_POST['cep'])) ||
        !has_max_length(preg_replace('/\D/', '', $_POST['cep']), 8)) {
        display_validation('cep', false);
        return;
    }

    if (!is_alpha_only($_POST['rua']) || !has_max_length($_POST['rua'], 50)) {
        display_validation('rua', false);
        return;
    }

    if (!is_numeric_only($_POST['numero']) || !has_max_length($_POST['numero'], 10)) {
        display_validation('numero', false);
        return;
    }

    if (!is_alpha_only($_POST['bairro']) || !has_max_length($_POST['bairro'], 50)) {
        display_validation('bairro', false);
        return;
    }

    if (!is_alpha_only($_POST['cidade']) || !has_max_length($_POST['cidade'], 50)) {
        display_validation('cidade', false);
        return;
    }

    if (!is_alpha_only($_POST['estado']) || !has_max_length($_POST['estado'], 2)) {
        display_validation('estado', false);
        return;
    }
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['cep'])) {
        submitInformation();
    }
}
try {
    $id_endereco_adicionado = create_address($conn, $_POST);
    $id_estoque_adicionado = create_inventory($conn, $_POST);
    create_settlement($conn, $_POST, $id_endereco_adicionado, $id_estoque_adicionado);
    showSucess(23);
} catch (Exception $e) {
    error_reporting(E_ALL);
}