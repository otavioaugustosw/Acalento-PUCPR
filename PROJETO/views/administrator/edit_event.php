<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . '/php/handlers/form_validator_php.php');
include_once (ROOT . "/components/sidebars/sidebars.php");
include_once (ROOT . "/models/admin_models_php.php");
include_once (ROOT . "/models/voluntary_models_php.php");

$conn = connectDatabase();
if (!isset($_GET['id'])) {
    showError(9);
}
$event_id =  intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['id'] )) {
    $did_update_event = update_event($conn, $_POST, $event_id);
    if (!$did_update_event) {
        showError(8);
    } else {
        showSucess(5);
    }
}

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
        <main class="px-5 row align-items-center justify-content-center">
            <div class="container-fluid">
                <div class="mb-3">
                    <!-- aqui vai o que você quer por -->
                    <h4>Evento</h4>
                    <form class="row g-3" method="POST" action="">
                        <!-- para três em uma linha -->
                        <div class="col-md-6">
                            <label for="inputNome" class="form-label">Nome*</label>
                            <input type="text" class="form-control" id="inputNome" name="nome" value="<?php echo $event->nome; ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="inputData" class="form-label">Data*</label>
                            <input type="date" class="form-control" id="inputData" placeholder="Data" name="data" value="<?php echo $event->data; ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="inputTime" class="form-label">Horário*</label>
                            <input type="time" class="form-control" id="inputTime" placeholder="Hora" name="hora" value="<?php echo $event->hora; ?>">
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
                            <input type="number" class="form-control" id="inputLotacao" name="lotacao_max" value="<?php echo $event->lotacao_max; ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="inputImagem" class="form-label">Insira imagem*</label>
                            <input type="text" class="form-control" id="inputImagem" name="link_media" value="<?php echo $event->link_media; ?>">
                        </div>

                        <!-- um em uma linha -->
                        <div class="col-12">
                            <label for="inputDescricao" class="form-label">Descrição*</label>
                            <textarea type="text" class="form-control" id="inputDescricao" name="descricao" rows="5"><?php echo $event->descricao; ?></textarea>
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