<?php
include (ROOT . "/php/config/database_php.php");
include(ROOT . '/php/handlers/form_validator_php.php');
include(ROOT .  "/components/sidebars/sidebars.php");

$conn = connectDatabase();
$id = (int)$_GET['id'];
if (!isset($_GET['id'])) {
    showError(9);
}

if (isset($_GET['id'])) {
    if (isset($_POST['descricao'])) {
        $query =
            "UPDATE evento 
            SET
            id_assentamento = '" . $_POST['id_assentamento'] . "',
            nome = '" . $_POST['nome'] . "',
            descricao = '" . $_POST['descricao'] . "',
            data = '" . $_POST['data'] . "',
            hora = '" . $_POST['hora'] . "',
            lotacao_max = " . $_POST['lotacao'] . ",
            link_imagem = '" . $_POST['imagem'] . "'
            WHERE id = " . $_GET['id'] . "
        ";


        $resultado = $conn->query($query);

        if (!$resultado) {
            showError(8);
        } else {
            showSucess(5);
        }
    }
}
$evento = $conn->query("SELECT * FROM evento WHERE id = $id")->fetch_object();
$assentamento = $conn->query("SELECT id, nome FROM assentamento");
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
                            <input type="text" class="form-control" id="inputNome" name="nome" value="<?php echo $evento->nome; ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="inputData" class="form-label">Data*</label>
                            <input type="date" class="form-control" id="inputData" placeholder="Data" name="data" value="<?php echo $evento->data; ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="inputTime" class="form-label">Horário*</label>
                            <input type="time" class="form-control" id="inputTime" placeholder="Hora" name="hora" value="<?php echo $evento->hora; ?>">
                        </div>

                        <!-- para dois em uma linha -->
                        <div class="col-md-4">
                            <label class="form-label">Assentamento</label>
                            <select name="id_assentamento" class="form-select">
                                <?php while ($a = $assentamento->fetch_object()) { ?>
                                    <option value="<?php echo $a->id; ?>" <?php echo ($a->id == $evento->id_assentamento) ? "selected" : ""; ?>>
                                        <?php echo $a->nome; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="inputLotacao" class="form-label">Lotação máxima*</label>
                            <input type="number" class="form-control" id="inputLotacao" name="lotacao" value="<?php echo $evento->lotacao_max; ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="inputImagem" class="form-label">Insira imagem*</label>
                            <input type="text" class="form-control" id="inputImagem" name="imagem" value="<?php echo $evento->link_imagem; ?>">
                        </div>

                        <!-- um em uma linha -->
                        <div class="col-12">
                            <label for="inputDescricao" class="form-label">Descrição*</label>
                            <textarea type="text" class="form-control" id="inputDescricao" name="descricao" rows="5"><?php echo $evento->descricao; ?></textarea>
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