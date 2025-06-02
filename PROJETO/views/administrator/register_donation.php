<?php
include(ROOT . "/php/config/database_php.php");
include(ROOT . '/php/handlers/form_validator_php.php');
include(ROOT .  "/components/sidebars/sidebars.php");
include(ROOT . "/php/auth_services/auth_service_php.php");
include(ROOT . "/components/back/back.php");
include_once (ROOT . '/models/admin_models_php.php');

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
                    <?php make_buttom_back("index.php?common=6"); ?>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/plentz/jquery-maskmoney@master/dist/jquery.maskMoney.min.js"></script>
<script>
    $(document).ready(function(){
        $('#inputValor').maskMoney({
            prefix: 'R$ ',
            allowNegative: false,
            thousands: '.',
            decimal: ',',
            affixesStay: true
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
    $id_usuario = $_POST['id_usuario'] == 0 ? null : $_POST['id_usuario'];
    $valor_bruto = $_POST['valor'] ?? '';
    $valor_limpo = str_replace(['R$', '.', ' '], '', $valor_bruto);
    $valor = (float) str_replace(',', '.', $valor_limpo);

    if ($id_usuario !== null && !is_numeric($id_usuario)) {
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

    if (!is_numeric($valor)) {
        display_validation('inputValor', false);
        return;
    }

    if ($campoArquivo === null) {
        display_validation('inputComprovante', false);
        return;
    }

    if (!is_numeric($valor)) {
        displayValidation('inputValor', false);
        return;
    }

    if (!isset($_FILES["comprovante"]) || $_FILES["comprovante"]["error"] !== UPLOAD_ERR_OK) {
        display_validation('inputComprovante', false);
        return;
    }

    $caminho = validateFile("comprovante", 'comprovantes');

    register_donation_monetary($conn, $id_usuario, $valor, $_POST['data'], $caminho , $validado);
}
