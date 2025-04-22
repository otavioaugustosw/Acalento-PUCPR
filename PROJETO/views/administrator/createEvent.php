<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include (ROOT . "/php/config/database_php.php");
include(ROOT . '/php/handlers/formValidator.php');
$obj = connectDatabase();
$assentamento = $obj->query("SELECT id, nome FROM assentamento");

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/form-style.css">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/sidebars.css">
    <link rel="stylesheet" href="css/main-content.css">
    <title>Acalento | Criar evento</title>
</head>

<body>
<?php include(ROOT . "/components/sidebars/sidebar-mobile.php") ?>
<div class="d-flex flex-nowrap">
    <?php include(ROOT .  "/components/sidebars/sidebars.php") ?>
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
                            <input type="text" class="form-control" id="inputNome" name="nome">
                            <div id="validacaoNome" class="invalid-feedback">
                                Digite um nome de evento válido.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="inputData" class="form-label">Data*</label>
                            <input type="date" class="form-control" id="inputData" placeholder="Data" name="data">
                            <div id="validacaoData" class="invalid-feedback">
                                Digite uma data.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="inputTime" class="form-label">Horário*</label>
                            <input type="time" class="form-control" id="inputTime" placeholder="Hora" name="hora">
                            <div id="validacaoHorario" class="invalid-feedback">
                                Digite um horário.
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="inputAssentamento" class="form-label">Local*</label>
                            <select name="id_assentamento" id="inputAssentamento" class="form-select">
                                <option value="">Selecione um assentamento</option>
                                <?php while ($a = $assentamento->fetch_object()) { ?>
                                <option value="<?php echo $a->id;?>"><?php echo $a->nome; ?></option>
                                <?php } ?>
                            </select>
                            <div id="validacaoAssentamento" class="invalid-feedback">
                                Selecione um assentamento.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="inputLotacao" class="form-label">Lotação máxima*</label>
                            <input type="text" class="form-control" id="inputLotacao" name="lotacao">
                            <div id="validacaoLotacaoMax" class="invalid-feedback">
                                Digite uma lotação máxima válida.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="inputImagem" class="form-label">Insira imagem*</label>
                            <input type="text" class="form-control" id="inputImagem" name="imagem">
                            <div id="validacaoImagem" class="invalid-feedback">
                                Digite um link válido.
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="inputDescricao" class="form-label">Descrição*</label>
                            <textarea type="text" class="form-control" id="inputDescricao" name="descricao" rows="5"></textarea>
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
    submitInformation($obj);
}

function submitInformation($sql) {

    if (!isAlphaOnly($_POST['nome']) || !hasMaxLength($_POST['nome'], 50)) {
        displayValidation('inputNome', false);
        return;
    }

    if (!isDateValid($_POST['data'])) {
        displayValidation('inputData', false);
        return;
    }

    if (!isTimeValid($_POST['hora'])) {
        displayValidation('inputHora', false);
        return;
    }

    if (!isNumericOnly($_POST['id_assentamento'])) {
        displayValidation('inputAssentamento', false);
        return;
    }

    if (!isNumericOnly($_POST['lotacao'])) {
        displayValidation('inputLotacao', false);
        return;
    }

    if (!hasMaxLength($_POST['imagem'], 256)) {
        displayValidation('inputImagem', false);
        return;
    }

    if (!isAlphaOnly($_POST['descricao']) || !hasMaxLength($_POST['descricao'], 100)) {
        displayValidation('inputDescricao', false);
        return;
    }


    try {
        $query = "
        INSERT INTO evento(id_assentamento, nome, descricao, lotacao_max, data, hora, link_imagem)
        VALUES ( ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $sql->prepare($query);
        $stmt->bind_param("ississs", $_POST['id_assentamento'], $_POST['nome'], $_POST['descricao'], $_POST['lotacao'], $_POST['data'], $_POST['hora'], $_POST['imagem']);
        $stmt->execute(); // executa query
        showSucess(2);
    } catch (mysqli_sql_exception $E) {
        showError(4);
        exit();
    }

}
