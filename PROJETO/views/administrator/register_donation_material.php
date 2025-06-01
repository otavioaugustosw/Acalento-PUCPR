<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . "/php/handlers/form_validator_php.php");
include_once (ROOT . "/components/sidebars/sidebars.php");
include_once (ROOT . "/php/auth_services/auth_service_php.php");
include_once (ROOT . "/components/back/back.php");
include_once (ROOT . "/models/donator_models_php.php");


$conn = connectDatabase();
load_user_session_data($conn);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <script src="assets/js/confirmation.js" defer></script>
    <link rel="stylesheet" href="css/form-style.css">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/main-content.css">
    <title>Acalento | Registrar doação</title>
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
                    <?php make_buttom_back("index.php?common=6"); ?>
                    <h2>Registrar doação</h2>
                    <form class="row g-3" method="POST" action="">

                        <!-- usuário -->
                        <div class="col-4">
                            <label for="inputDoador" class="form-label">Doador*</label>
                            <select name="id_usuario" id="inputDoador" class="form-select">
                                <option value="">Selecione o doador</option>
                                <?php $doador = $conn->query("SELECT id, nome FROM usuario");
                                while ($a = $doador->fetch_object()) { ?>
                                    <option value="<?php echo $a->id;?>" <?= (isset($_POST['id_usuario']) && $_POST['id_usuario'] == $a->id) ? 'selected' : '' ?>>
                                        <?php echo $a->nome; ?>
                                    </option>
                                <?php } ?>
                                <option value="0">Doador não cadastrado</option>
                            </select>
                            <div id="validacaoUsuario" class="invalid-feedback">
                                Escolha um doador.
                            </div>
                        </div>

                        <!-- data -->
                        <div class="col-md-4">
                            <label for="inputData" class="form-label">Data*</label>
                            <input type="date" class="form-control" id="inputData" placeholder="Data" name="data" value="<?= $_POST['data'] ?? null ?>">
                            <div id="validacaoData" class="invalid-feedback">
                                Escolha uma data.
                            </div>
                        </div>

                        <!-- item -->
                        <div class="col-md-4" id="campoItem">
                            <label for="inputItem" class="form-label">Item*</label>
                            <select name="id_opcao_item_doacao" id="inputItem" class="form-select">
                                <option value="">Selecione o item</option>
                                <?php $item = $conn->query("SELECT id, nome FROM opcao_item_doacao");
                                while ($a = $item->fetch_object()) { ?>
                                    <option value="<?php echo $a->id;?>" <?= (isset($_POST['id_opcao_item_doacao']) && $_POST['id_opcao_item_doacao'] == $a->id) ? 'selected' : '' ?>><?php echo $a->nome; ?></option>
                                <?php } ?>
                            </select>
                            <div id="validacaoItem" class="invalid-feedback">
                                Escolha um item
                            </div>
                        </div>

                        <!-- quantidade -->
                        <div class="col-md-4" id="campoQuantidade">
                            <label for="inputQuantidade" class="form-label">Quantidade</label>
                            <input type="number" class="form-control" id="inputQuantidade" name="quantidade" value="<?= $_POST['quantidade'] ?? null ?>">
                            <div id="validacaoQuantidade" class="invalid-feedback">
                                Digite uma quantidade válida.
                            </div>
                        </div>

                        <!-- unidade de medida -->
                        <div class="col-md-4" id="campoUnidadeMedida">
                            <label for="inputUnidadeMedida" class="form-label">Unidade de Medida</label>
                            <select id="inputUnidadeMedida" name="unidade_medida" class="form-select">
                                <option value="">Selecione a unidade de medida</option>
                                <option value="mg" <?= (($_POST['unidade_medida'] ?? '') == 'mg') ? 'selected' : '' ?>>Miligrama</option>
                                <option value="g" <?= (($_POST['unidade_medida'] ?? '') == 'g') ? 'selected' : '' ?>>Grama</option>
                                <option value="kg" <?= (($_POST['unidade_medida'] ?? '') == 'kg') ? 'selected' : '' ?>>Quilograma</option>
                                <option value="ml" <?= (($_POST['unidade_medida'] ?? '') == 'ml') ? 'selected' : '' ?>>Mililitro</option>
                                <option value="l" <?= (($_POST['unidade_medida'] ?? '') == 'l') ? 'selected' : '' ?>>Litro</option>
                                <option value="u" <?= (($_POST['unidade_medida'] ?? '') == 'u') ? 'selected' : '' ?>>Unidade(s)</option>
                            </select>
                            <div id="validacaoUnidadeMedida" class="invalid-feedback">
                                Selecione uma unidade de medida.
                            </div>
                        </div>

                        <!-- categoria -->
                        <div class="col-md-4" id="campoCategoria">
                            <label for="inputCategoria" class="form-label">Categoria*</label>
                            <select id="inputCategoria" name="categoria" class="form-select">
                                <option value="">Selecione o tipo</option>
                                <?php
                                // Valores fixos do ENUM
                                $valores_enum = ['Alimentício', 'Brinquedo', 'Limpeza', 'Outros'];

                                foreach ($valores_enum as $valor) { ?>
                                    <option value="<?php echo $valor; ?>" <?= (($_POST['categoria'] ?? '') == $valor) ? 'selected' : '' ?>>
                                        <?php echo $valor; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <div id="validacaoTipo" class="invalid-feedback">
                                Selecione um tipo de item.
                            </div>
                        </div>

                        <div class="col-md-12" id="campoEstoque">
                            <label class="form-label">Estoque</label>
                            <select id=inputEstoque name="id_estoque" class="form-select">
                                <option value="1">Estoque geral</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Salvar item</button>
                        </div>
                    </form>
                    <!-- aqui termina -->
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Choices('#inputDoador', {
            searchEnabled: true,
            itemSelectText: '',
            shouldSort: false
        });
    });
</script>


<script>
    document.querySelector("form").addEventListener("submit", function () {
        const select = document.getElementById("inputDoador");
        const wrapper = select?.closest(".choices");
        const feedback = document.getElementById("validacaoUsuario");

        if (wrapper) {
            wrapper.style.removeProperty('--default-border');
        }

        if (feedback) {
            feedback.style.display = "none";
        }
    });
</script>
</body>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    submitInformation($conn);
}


function submitInformation($conn)
{

    $id_usuario = $_POST['id_usuario'] == 0 ? null : $_POST['id_usuario'];
    $idEstoque = $_POST['id_estoque'];
    $quantidade = $_POST['quantidade'];
    $unidadeMedida = $_POST['unidade_medida'];
    $opcoes_validas_um = ['mg', 'g', 'kg', 'ml', 'l', 'u'];
    $opcoes_validas_categoria = ['Alimentício', 'Brinquedo', 'Limpeza', 'Outros'];

    if (!is_numeric($id_usuario)) {
        ?>
        <script>
            setTimeout(() => {
                const select = document.getElementById("inputDoador");
                const wrapper = select?.closest(".choices");
                const feedback = document.getElementById("validacaoUsuario");

                if (wrapper) {
                    wrapper.style.setProperty('--default-border', '2px solid #e53935');
                }

                if (feedback) {
                    feedback.style.display = "block";
                }
            }, 150); // tempo suficiente pro Choices montar o HTML
        </script>
        <?php
        return;
    }

    if (!is_date_valid($_POST['data'])) {
        display_validation('inputData', false);
        return;
    }

    if (!is_numeric($idEstoque)) {
        display_validation('inputEstoque', false);
        return;
    }

    if (!is_numeric($_POST['id_opcao_item_doacao'])) {
        display_validation('inputItem', false);
        return;
    }

    if (!is_numeric($quantidade)) {
        display_validation('inputQuantidade', false);
        return;
    }

    if (!in_array($unidadeMedida, $opcoes_validas_um) && (!is_alpha_only($unidadeMedida) || !has_max_length($unidadeMedida, 3))) {
        display_validation('inputUnidadeMedida', false);
        return;
    }

    if (!in_array($_POST['categoria'], $opcoes_validas_categoria) && !is_alpha_only($_POST['categoria'])) {
        display_validation('inputCategoria', false);
        return;
    }

    $did_create_donation = create_material_donation($conn, $idEstoque, $id_usuario, $_POST);

    if ($did_create_donation) {
        echo '<script>
    window.addEventListener("load", function () {
        if (typeof clearFormManual === "function") {
            clearFormManual();
        } else {
            console.error("clearFormManual não está disponível");
        }
    });
    </script>';
        showSucess(3);
    }
    else {
        showError(5);
    }
}