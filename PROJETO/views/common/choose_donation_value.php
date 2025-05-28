<?php
include_once (ROOT . '/components/progress-bar/progress-bar.php');
include_once (ROOT . '/php/config/session_php.php');
include_once (ROOT . '/php/config/database_php.php');
include_once (ROOT . '/php/auth_services/auth_service_php.php');
include_once (ROOT . '/php/handlers/form_validator_php.php');
include_once (ROOT .  "/components/back/back.php");

$conn = connectDatabase();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acalento | Início</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/main-content.css">
    <link rel="stylesheet" href="css/progress-bar.css">
    <link rel="stylesheet" href="css/form-style.css">
</head>
<body>
<div class="position-fixed top-0 start-0 w-100 z-3 py-3">
    <?php render_progress_bar(2); ?>
</div>

<!-- Conteúdo centralizado -->
<div class="d-flex justify-content-center align-items-center min-vh-100 pt-5">
    <div class="row shadow rounded overflow-hidden justify-content-center bg-white" style="width: 55%; height: 50vh;">
        <div class="col-md-8 d-flex flex-column justify-content-center gap-4">
            <?php make_buttom_onclick(); ?>
            <h2 class="text-center">Escolha o valor da sua doação</h2>

            <form action="index.php?common=13" method="POST" class="d-flex flex-column align-items-center gap-3">
                <div class="mb-3 w-100">
                    <label for="valor" class="form-label">Valor</label>
                    <input type="text" class="form-control text-center" name="valor" id="valor" required placeholder="R$00,00"
                    value="">
                </div>
                <button type="submit" class="btn btn-primary ">Avançar para o pagamento</button>
            </form>
        </div>
    </div>
</div>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/plentz/jquery-maskmoney@master/dist/jquery.maskMoney.min.js"></script>
<script>
    $(document).ready(function(){
        $('#valor').maskMoney({
            prefix: 'R$ ',
            allowNegative: false,
            thousands: '.',
            decimal: ',',
            affixesStay: true
        });
    });
</script>

</html>
