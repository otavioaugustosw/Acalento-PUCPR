<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . '/php/handlers/form_validator_php.php');
include_once (ROOT . "/components/sidebars/sidebars.php");
include_once (ROOT . "/models/admin_models_php.php");
include_once (ROOT . "/models/voluntary_models_php.php");
include_once (ROOT .  "/components/back/back.php");

$conn = connectDatabase();
if (!isset($_GET['id'])) {
    showError(9);
}
$event_id =  intval($_GET['id']);
$event = get_events_where($conn, "WHERE evento.id = $event_id", $_SESSION['USER_ID'])->fetch_object();
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
    <title>Acalento | Atualizar Evento</title>
</head>

<body>
<?php make_mobile_sidebar() ?>
<div class="d-flex flex-nowrap">
    <!--    monta a sidebar desktop-->
    <?php make_sidebar(); ?>
    <!-- fim sidebar -->

    <!-- conteudo -->
    <div class="main-content">
        <main class="px-5 row">
            <div class="container-fluid">
                <div class="mb-4"><?php make_buttom_back("index.php?adm=5");?></div>
                <div class="mb-3">
                    <!-- aqui vai o que você quer por -->
                    <h4>Evento</h4>
                    <form class="row g-3" method="POST" action="" enctype="multipart/form-data">
                        <!-- para três em uma linha -->
                        <div class="col-md-6">
                            <label for="inputNome" class="form-label">Nome*</label>
                            <input type="text" class="form-control" id="inputNome" name="nome" value="<?php echo $_POST['nome'] ?? $event->nome; ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="inputData" class="form-label">Data*</label>
                            <input type="date" class="form-control" id="inputData" placeholder="Data" name="data" value="<?php echo $_POST['data'] ?? $event->data; ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="inputTime" class="form-label">Horário*</label>
                            <input type="time" class="form-control" id="inputTime" placeholder="Hora" name="hora" value="<?php echo $_POST['hora'] ?? $event->hora; ?>">
                        </div>

                        <!-- para dois em uma linha -->
                        <div class="col-md-4">
                            <label class="form-label">Assentamento</label>
                            <select name="id_assentamento" class="form-select">
                                <?php while ($settlement = $settlements->fetch_object()) { ?>
                                    <option value="<?php echo $settlement->id; ?>" <?php echo ($settlement->id == $event->id_assentamento) ? "selected" : ""; ?>>
                                        <?php echo $settlement->nome; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="inputLotacao" class="form-label">Lotação máxima*</label>
                            <input type="number" class="form-control" id="inputLotacao" name="lotacao_max" value="<?php echo $_POST['lotacao_max'] ?? $event->lotacao_max; ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="inputImagem" class="form-label">Insira imagem*</label>
                            <input type="file" class="form-control" id="inputImagem" name="link_media" value="<?php echo $_POST['link_media'] ?? $event->link_media; ?>">
                        </div>

                        <!-- um em uma linha -->
                        <div class="col-12">
                            <label for="inputDescricao" class="form-label">Descrição*</label>
                            <textarea type="text" class="form-control" id="inputDescricao" name="descricao" rows="5"><?php echo$_POST['descricao'] ?? $event->descricao; ?></textarea>
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
    submitInformation($conn, $event);
}

function submitInformation($conn, $event) {

    if (!has_max_length($_POST['nome'], 50)) {
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

    $image = $event->link_media;

    if (isset($_FILES['link_media']) && $_FILES['link_media']['error'] === UPLOAD_ERR_OK) {
        $novoArquivo = validateFile('link_media', 'media');
        if ($novoArquivo) {
            $image = $novoArquivo;
        }
    }

    if (!has_max_length($image, 256)) {
        display_validation('inputImagem', false);
        return;
    }

    if (!has_max_length($_POST['descricao'], 100)) {
        display_validation('inputDescricao', false);
        return;
    }

    $did_update_event = update_event(
        $conn,
        $_POST,
        $event->id,
        $image
    );

    if ($did_update_event) {
        showSucess(2);
    }
    else {
        showError(4);
    }
}