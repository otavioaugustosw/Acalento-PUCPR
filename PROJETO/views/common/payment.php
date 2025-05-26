<?php

include_once (ROOT . '/components/progress-bar/progress-bar.php');
include_once (ROOT . '/php/config/session_php.php');
include_once (ROOT . '/php/config/database_php.php');
include_once (ROOT . '/php/auth_services/auth_service_php.php');
include_once (ROOT . '/php/handlers/form_validator_php.php');
include_once (ROOT . '/php/handlers/payment_handler.php');
include_once (ROOT . '/models/admin_models_php.php');

$conn = connectDatabase();
load_user_session_data($conn);

// Obtém valor da doação
$valorBruto = $_SESSION['valor_doacao'] ?? null;
if (!$valorBruto) {
    exit;
}

$valor = (float) str_replace(',', '.', $valorBruto);

$chave = '70230618600';
$nome = 'ANNA QUEZIA DOS SANTOS';
$cidade = 'CURITIBA';
$txid = uniqid();

$pixCode = geraPixCode($chave, $valor, $nome, $cidade, $txid);
$qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($pixCode);

// Upload do comprovante

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['comprovante'])) {
    $arquivo = validateFile('comprovante', 'comprovantes');
    if ($arquivo) {
            date_default_timezone_set('America/Sao_Paulo');
            $data = date('Y-m-d');
            $validado = 0;
            register_donation_monetary($conn, $_SESSION['USER_ID'], $valor, $data, $arquivo, $validado);
            load_user_session_data($conn);
            header("Location: index.php?common=15");
            exit;
        }

}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pagamento Pix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/main-content.css">
    <link rel="stylesheet" href="css/progress-bar.css">
    <link rel="stylesheet" href="css/form-style.css">
</head>
<body class="d-flex justify-content-center align-items-center">

<div class="position-fixed top-0 start-0 w-100 z-3 py-3">
    <?php render_progress_bar(3); ?>
</div>

<div class="container">
    <div class="card shadow-lg mx-auto p-4" style="max-width: 600px; margin-top: 150px">
        <h2 class="text-center mb-3">Realize o pagamento</h2>
        <p class="text-center text-muted">Valor: <strong>R$ <?= number_format($valor, 2, ',', '.') ?></strong></p>

        <div class="text-center mb-4">
            <img src="<?= $qrUrl ?>" alt="QR Code Pix" class="img-fluid" style="max-width: 200px;">
        </div>

        <div class="bg-body-secondary rounded p-3 mb-4 text-center">
            <p class="mb-1 fw-semibold">Copia e Cola:</p>
            <textarea class="form-control small" rows="3" readonly><?= $pixCode ?></textarea>
        </div>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="comprovante" class="form-label fw-semibold">Comprovante de pagamento</label>
                <input type="file" name="comprovante" id="comprovante" class="form-control" required accept=".jpg,.jpeg,.png,.pdf">
            </div>
            <button type="submit" class="btn btn-primary w-100">Enviar comprovante</button>
        </form>
    </div>
</div>
</body>
</html>

