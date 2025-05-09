<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . '/php/handlers/form_validator_php.php');
include_once (ROOT .  "/components/sidebars/sidebars.php");
include_once (ROOT .  "/models/voluntary_models_php.php");
include_once (ROOT . "/models/admin_models_php.php");

// conexão com o banco de dados
$conn = connectDatabase();

// busca no banco de dados por assentamentos
$settlements = get_all_settlements($conn);

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
                    <!-- aqui vai o que você quer por -->
                    <h4>Evento</h4>
                    <form class="row g-3" method="POST" action="">
                        <div class="col-md-6">
                            <label for="inputNome" class="form-label">Nome*</label>
                            <input type="text" class="form-control" id="inputNome" name="nome" value="<?= $_POST['nome'] ?? null ?>">
                            <div id="validacaoNome" class="invalid-feedback">
                                Digite um nome de evento válido.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="inputData" class="form-label">Data*</label>
                            <input type="date" class="form-control" id="inputData" placeholder="Data" name="data" value="<?= $_POST['data'] ?? null ?>">
                            <div id="validacaoData" class="invalid-feedback">
                                Digite uma data.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="inputTime" class="form-label">Horário*</label>
                            <input type="time" class="form-control" id="inputTime" placeholder="Hora" name="hora" value="<?= $_POST['hora'] ?? null ?>">
                            <div id="validacaoHorario" class="invalid-feedback">
                                Digite um horário.
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="inputAssentamento" class="form-label">Local*</label>
                            <select name="id_assentamento" id="inputAssentamento" class="form-select">
                                <option value="">Selecione um assentamento</option>
                                <?php while ($a = $settlements->fetch_object()) { ?>
                                <option value="<?php echo $a->id;?>" <?= (isset($_POST['id_assentamento']) && $_POST['id_assentamento'] == $a->id) ? 'selected' : '' ?>><?php echo $a->nome; ?></option>
                                <?php } ?>
                            </select>
                            <div id="validacaoAssentamento" class="invalid-feedback">
                                Selecione um assentamento.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="inputLotacao" class="form-label">Lotação máxima*</label>
                            <input type="text" class="form-control" id="inputLotacao" name="lotacao_max" value="<?= $_POST['lotacao_max'] ?? null ?>">
                            <div id="validacaoLotacaoMax" class="invalid-feedback">
                                Digite uma lotação máxima válida.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="inputImagem" class="form-label">Insira imagem*</label>
                            <input type="text" class="form-control" id="inputImagem" name="link_media" value="<?= $_POST['link_media'] ?? null ?>">
                            <div id="validacaoImagem" class="invalid-feedback">
                                Digite um link válido.
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="inputDescricao" class="form-label">Descrição*</label>
                            <textarea type="text" class="form-control" id="inputDescricao" name="descricao" rows="5"><?= $_POST['descricao'] ?? null ?></textarea>
                            <div id="validacaoDescricao" class="invalid-feedback">
                                Digite uma descricao válida.
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Salvar evento</button>
                        </div>
                    </form>
                    <!-- aqui termina -->
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    submitInformation($conn);
}

function submitInformation($conn) {

    if (!is_alpha_only($_POST['nome']) || !has_max_length($_POST['nome'], 50)) {
        display_validation('inputNome', false);
        return;
    }

    if (!is_date_valid($_POST['data'])) {
        display_validation('inputData', false);
        return;
    }

    if (!is_numeric_only($_POST['id_assentamento'])) {
        display_validation('inputAssentamento', false);
        return;
    }

    if (!is_numeric_only($_POST['lotacao_max'])) {
        display_validation('inputLotacao', false);
        return;
    }

    if (!has_max_length($_POST['link_media'], 256)) {
        display_validation('inputImagem', false);
        return;
    }

    if (!is_alpha_only($_POST['descricao']) || !has_max_length($_POST['descricao'], 100)) {
        display_validation('inputDescricao', false);
        return;
    }

    $did_create_event = create_event(
        $conn,
        $_POST
    );

    if ($did_create_event) {
        showSucess(2);
    }
    else {
        showError(4);
    }
}
