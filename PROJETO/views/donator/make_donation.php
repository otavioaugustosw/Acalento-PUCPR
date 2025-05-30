<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . "/components/sidebars/sidebars.php");
include_once (ROOT . "/components/table/tables.php");
include_once (ROOT . "/components/cards/cards.php");
include_once (ROOT . "/models/donator_models_php.php");
include_once (ROOT .  "/components/back/back.php");

$conn = connectDatabase();

?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/default.css">
        <link rel="stylesheet" href="css/form-style.css">
        <link rel="stylesheet" href="css/cards.css">
        <link rel="stylesheet" href="css/sidebar.css">
        <link rel="stylesheet" href="css/main-content.css">
        <title>Acalento | Validar pagamento</title>
    </head>

<body>
<?php make_mobile_sidebar() ?>
<div class="d-flex flex-nowrap">
<?php make_sidebar(); ?>
    <div class="main-content">
        <main class="px-5 row addScroll">
            <div class="container-fluid">
                <div class="mb-3">
                    <?php make_buttom_back("");?>
                    <div class="d-flex justify-content-center min-vh-100 pt-5">
                        <div class="row shadow rounded overflow-hidden justify-content-center bg-white" style="width: 80%; height: 50vh;">
                            <div class="col-md-8 d-flex flex-column justify-content-center gap-4">
                                <h2 class="text-center">Escolha o valor da sua doação</h2>

                                <form action="" method="POST" class="d-flex flex-column align-items-center gap-3">
                                    <div class="mb-3 w-100">
                                        <label for="valor" class="form-label">Valor</label>
                                        <input type="text" class="form-control text-center" name="valor" id="inputValor" placeholder="R$00,00"
                                               value="">
                                        <div id="validacaoValor" class="invalid-feedback">
                                            Digite um valor válido
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary ">Avançar para o pagamento</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
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
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    submitInformation($conn);
}

function submitInformation($conn) {
    $valor = $_POST["valor"] ?? '';
    $valorLimpo = preg_replace('/\D/', '', $valor);

    if (!is_numeric_only($valorLimpo)) {
        display_validation('inputValor', false);
        return;
    }

    $_SESSION['valor_doacao'] = $valor;
    echo "<script>window.location.href = 'index.php?donator=3';</script>";
    exit;
}
?>
