<?php
include(ROOT . "/php/config/database_php.php");
include(ROOT . '/php/handlers/form_validator_php.php');
include(ROOT .  "/components/sidebars/sidebars.php");
include(ROOT . "/php/auth_services/auth_service_php.php");
include(ROOT . "/components/back/back.php");


$conn = connectDatabase();
load_user_session_data($conn);
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
                    <?php make_buttom_back();?>
                    <h2>Registrar doação</h2>
                    <form class="row g-3" method="POST" enctype="multipart/form-data" action="">

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

                        <!-- tipo monetário -->

                        <div class="col-md-4" id="campoValor">
                            <label for="inputValor" class="form-label">Valor*</label>
                            <input type="text" class="form-control" id="inputValor" name="valor" placeholder="R$00.00" value="<?= $_POST['valor'] ?? null ?>">
                            <div id="validacaoValor" class="invalid-feedback">
                                Digite um valor válido.
                            </div>
                        </div>

                        <div class="col-12" id="campoComprovante">
                            <label for="inputComprovante" class="form-label">Comprovante*</label>
                            <input type="file" class="form-control" id="inputComprovante" name="comprovante">
                            <div id="validacaoComprovante" class="invalid-feedback">
                                Envie o comprovante.
                            </div>
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
</body>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    submitInformation($conn);
}


function submitInformation($conn)
{
    $validado = 1;

    $campoArquivo = $_FILES["comprovante"];
    $caminho = validateFile("comprovante");

    $id_usuario = $_POST['id_usuario'] == 0 ? null : $_POST['id_usuario'];
    $valor = isset($_POST['valor']) && $_POST['valor'] !== '' ? (float) $_POST['valor'] : null;

    if ($id_usuario !== null && !is_numeric($id_usuario)) {
        display_validation('inputDoador', false);
        return;
    }

    if (!is_date_valid($_POST['data'])) {
        display_validation('inputData', false);
        return;
    }

    if (!is_numeric($valor)) {
        display_validation('inputValor', false);
        return;
    }

    if ($campoArquivo === null) {
        display_validation('inputComprovante', false);
        return;
    }

    try {
        $query = "
        INSERT INTO doacao_monetaria(id_usuario, valor, data, link_media, validado)
        VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("idssi", $id_usuario, $valor, $_POST['data'], $caminho , $validado);
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
        ob_start(); // começa a capturar a saída
        var_dump($e->getMessage());
        $dump = ob_get_clean(); // salva a saída em uma variável

        echo "<div id='debug-dump'>{$dump}</div>";
    }
}
