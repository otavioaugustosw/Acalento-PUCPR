<?php
include (ROOT . "/php/config/database_php.php");
include(ROOT . '/php/handlers/form_validator_php.php');
include(ROOT .  "/components/sidebars/sidebars.php");
include (ROOT . "/php/auth_services/auth_service_php.php");

$conn = connectDatabase();
load_user_session_data($conn);
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
        <main class="px-5 row align-items-center justify-content-center">
            <div class="container-fluid">
                <div class="mb-3">
                    <!-- aqui vai o que você quer por -->
                    <h4>Registrar doação</h4>
                    <form class="row g-3" method="POST" action="">
                        <div class="col-md-4">
                            <label for="inputDoador" class="form-label">Doador*</label>
                            <select name="id_usuario" id="inputDoador" class="form-select">
                                <option value="">Selecione o doador</option>
                                <?php $doador = $conn->query("SELECT id, nome FROM usuario");
                                while ($a = $doador->fetch_object()) { ?>
                                    <option value="<?php echo $a->id;?>" <?= (isset($_POST['id_usuario']) && $_POST['id_usuario'] == $a->id) ? 'selected' : '' ?>><?php echo $a->nome; ?></option>
                                <?php } ?>
                                <option value="0">Doador não cadastrado</option>
                            </select>
                            <div id="validacaoUsuario" class="invalid-feedback">
                                Escolha um doador.
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="inputData" class="form-label">Data*</label>
                            <input type="date" class="form-control" id="inputData" placeholder="Data" name="data" value="<?= $_POST['data'] ?? null ?>">
                            <div id="validacaoData" class="invalid-feedback">
                                Escolha uma data.
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="inputItem" class="form-label">Item*</label>
                            <select name="id_opcao" id="inputItem" class="form-select">
                                <option value="">Selecione o item</option>
                                <?php $item = $conn->query("SELECT id, nome FROM opcao_item");
                                while ($a = $item->fetch_object()) { ?>
                                    <option value="<?php echo $a->id;?>" <?= (isset($_POST['id_opcao']) && $_POST['id_opcao'] == $a->id) ? 'selected' : '' ?>><?php echo $a->nome; ?></option>
                                <?php } ?>
                            </select>
                            <div id="validacaoItem" class="invalid-feedback">
                                Escolha um item
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="inputQuantidade" class="form-label">Quantidade</label>
                            <input type="number" class="form-control" id="inputQuantidade" name="quantidade" value="<?= $_POST['quantidade'] ?? null ?>">
                            <div id="validacaoQuantidade" class="invalid-feedback">
                                Digite uma quantidade válida.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="inputUnidadeMedida" class="form-label">Unidade de Medida</label>
                            <select id="inputUnidadeMedida" name="unidade_medida" class="form-select">
                                <option value="">Selecione a unidade de medida</option>
                                <option value="mg" <?= (($_POST['unidade_medida'] ?? '') == 'mg') ? 'selected' : '' ?>>Miligrama</option>
                                <option value="g" <?= (($_POST['unidade_medida'] ?? '') == 'g') ? 'selected' : '' ?>>Grama</option>
                                <option value="kg" <?= (($_POST['unidade_medida'] ?? '') == 'kg') ? 'selected' : '' ?>>Quilograma</option>
                                <option value="ml" <?= (($_POST['unidade_medida'] ?? '') == 'ml') ? 'selected' : '' ?>>Mililitro</option>
                                <option value="l" <?= (($_POST['unidade_medida'] ?? '') == 'l') ? 'selected' : '' ?>>Litro</option>
                            </select>
                            <div id="validacaoUnidadeMedida" class="invalid-feedback">
                                Selecione uma unidade de medida.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="inputValor" class="form-label">Valor</label>
                            <input type="text" class="form-control" id="inputValor" name="valor" placeholder="R$00.00" value="<?= $_POST['valor'] ?? null ?>">
                            <div id="validacaoValor" class="invalid-feedback">
                                Digite um valor válido.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="inputTipo" class="form-label">Tipo*</label>
                            <select id="inputTipo" name="tipo" class="form-select">
                                <option value="">Selecione o tipo</option>
                                <?php
                                // Valores fixos do ENUM
                                $valores_enum = ['Alimentício', 'Brinquedo', 'Limpeza', 'Outros'];

                                foreach ($valores_enum as $valor) { ?>
                                    <option value="<?php echo $valor; ?>" <?= (($_POST['tipo'] ?? '') == $valor) ? 'selected' : '' ?>>
                                        <?php echo $valor; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <div id="validacaoTipo" class="invalid-feedback">
                                Selecione um tipo de item.
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Destino do item*</label>
                            <select id="selectDestino" name="destino" class="form-select" onchange="mostrarDestino()">
                                <option value="">Selecione</option>
                                <option value="campanha" <?= (($_POST['destino'] ?? '') == 'campanha') ? 'selected' : '' ?>>Campanha</option>
                                <option value="estoque" <?= (($_POST['destino'] ?? '') == 'estoque')  ? 'selected' : '' ?>>Estoque</option>
                            </select>
                        </div>

                        <div class="col-md-10" id="campoCampanha" style="display: none;">
                            <label class="form-label">Campanha</label>
                            <select id=inputCampanha name="id_campanha_doacao" class="form-select">
                                <option value="">Selecione a campanha</option>
                                <?php
                                $campanhas = $conn->query("SELECT id, nome FROM campanha_doacao");
                                while ($a = $campanhas->fetch_object()) { ?>
                                    <option value="<?php echo $a->id; ?> " <?= (isset($_POST['id_campanha_doacao']) && $_POST['id_campanha_doacao'] == $a->id) ? 'selected' : '' ?>><?php echo $a->nome; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-10" id="campoEstoque" style="display: none;">
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

<script>

    function mostrarDestino() {
        // pega o valor de seleção de destino e dependendo do valor uma opção fica visivel ou a outra
        const tipo = document.getElementById('selectDestino').value;
        document.getElementById('campoCampanha').style.display = tipo === 'campanha' ? 'block' : 'none';
        document.getElementById('campoEstoque').style.display = tipo === 'estoque' ? 'block' : 'none';
    }
</script>
</body>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    submitInformation($conn);
}

function submitInformation($conn)
{
    $idCampanha = ($_POST['destino'] === 'campanha' && isset($_POST['id_campanha_doacao'])) ? (int) $_POST['id_campanha_doacao'] : null;
    $id_usuario = $_POST['id_usuario'] == 0 ? null : $_POST['id_usuario'];
    $idEstoque  = ($_POST['destino'] === 'estoque'  && isset($_POST['id_estoque'])) ? (int) $_POST['id_estoque'] : null;
    $quantidade = isset($_POST['quantidade']) && $_POST['quantidade'] !== '' ? (int) $_POST['quantidade'] : null;
    $unidadeMedida = isset($_POST['unidade_medida']) && $_POST['unidade_medida'] !== '' ? $_POST['unidade_medida'] : null;
    $valor = isset($_POST['valor']) && $_POST['valor'] !== '' ? (float) $_POST['valor'] : null;
    $opcoes_validas_um = ['mg', 'g', 'kg', 'ml', 'l'];
    $opcoes_validas_categoria = ['Alimentício', 'Brinquedo', 'Limpeza', 'Outros'];

    if ($idEstoque !== null && !isNumericOnly($idEstoque)) {
        displayValidation('inputEstoque', false);
        return;
    }

    if ($idCampanha !== null && !isNumericOnly($idCampanha)) {
        displayValidation('inputCampanha', false);
        return;
    }

    if (!isNumericOnly($_POST['id_opcao'])) {
        displayValidation('inputItem', false);
        return;
    }

    if ($id_usuario !== null && !isNumericOnly($id_usuario)) {
        displayValidation('inputDoador', false);
        return;
    }

    if ($quantidade !== null && !isNumericOnly($quantidade)) {
        displayValidation('inputQuantidade', false);
        return;
    }

    if ($unidadeMedida !== null && !in_array($unidadeMedida, $opcoes_validas_um) && (!isAlphaOnly($unidadeMedida) || !hasMaxLength($unidadeMedida, 3))) {
        displayValidation('inputUnidadeMedida', false);
        return;
    }

    if ($valor !== null && !is_numeric($valor)) {
        displayValidation('inputValor', false);
        return;
    }

    if (!in_array($opcoes_validas_categoria, $_POST['tipo']) && !isAlphaOnly($_POST['tipo'])) {
        displayValidation('inputTipo', false);
        return;
    }

    if (!isDateValid($_POST['data'])) {
        displayValidation('inputData', false);
        return;
    }

    try {
        $query = "
        INSERT INTO item(id_campanha_doacao, id_estoque, id_opcao, id_usuario, quantidade, unidade_medida, valor, tipo, data) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiiiisdss", $idCampanha, $idEstoque, $_POST['id_opcao'], $id_usuario, $quantidade, $unidadeMedida, $valor, $_POST['tipo'], $_POST['data']);
        $stmt->execute();
        if ($id_usuario != null) {
            $query = "UPDATE usuario SET eh_doador = 1 WHERE id = ?";
            load_user_session_data($conn);
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
        }
        showSucess(3);

    } catch (mysqli_sql_exception $e) {
        showError(5);
        var_dump( $e->getMessage());
    }
}
