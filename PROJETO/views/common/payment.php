<?php

include_once (ROOT . '/components/progress-bar/progress-bar.php');
include_once (ROOT . '/php/config/session_php.php');
include_once (ROOT . '/php/config/database_php.php');
include_once (ROOT . '/php/auth_services/auth_service_php.php');
include_once (ROOT . '/php/handlers/form_validator_php.php');

$valor = $_SESSION['valor_doacao'] ?? null;
if (!$valor) {
    header('Location: escolher-valor.php');
    exit;
}

$valorFormatado = number_format((float)str_replace(',', '.', $valor), 2, '.', '');

// Dados da ONG
$chavePix = '12345678000199'; // ou e-mail Pix
$nome = 'ACALENTO ONG';
$cidade = 'SAO PAULO';
$txid = 'doacao-' . time();

// Gera código Pix simples (usável em apps bancários)
$pixCopiaCola = "00020126360014BR.GOV.BCB.PIX0111{$chavePix}520400005303986540" . strlen($valorFormatado) . "{$valorFormatado}5802BR5914{$nome}6009{$cidade}62070503***6304";
$qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($pixCopiaCola);

// Mensagem de status após upload
$msg = '';

// Envio do comprovante
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['comprovante'])) {
    $file = $_FILES['comprovante'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'comprovante_' . time() . '.' . $ext;
        $uploadDir = __DIR__ . '/uploads/comprovantes/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $path = $uploadDir . $filename;
        if (move_uploaded_file($file['tmp_name'], $path)) {
            $msg = '<div class="alert alert-success mt-3">Comprovante enviado com sucesso!</div>';
        } else {
            $msg = '<div class="alert alert-danger mt-3">Erro ao salvar o comprovante.</div>';
        }
    } else {
        $msg = '<div class="alert alert-warning mt-3">Erro no envio do arquivo.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pagamento Pix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center min-vh-100">

<div class="container">
    <div class="card shadow-lg mx-auto p-4" style="max-width: 600px;">
        <h2 class="text-center mb-3">Realize o pagamento</h2>
        <p class="text-center text-muted">Valor: <strong>R$ <?= number_format($valor, 2, ',', '.') ?></strong></p>

        <div class="text-center mb-4">
            <img src="<?= $qrUrl ?>" alt="QR Code Pix" class="img-fluid" width="200">
        </div>

        <div class="bg-body-secondary rounded p-3 mb-4 text-center">
            <p class="mb-1 fw-semibold">Chave Pix (CNPJ):</p>
            <p class="mb-2 small"><?= $chavePix ?></p>
            <button class="btn btn-outline-primary btn-sm" onclick="navigator.clipboard.writeText('<?= $chavePix ?>')">Copiar chave</button>
        </div>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="comprovante" class="form-label fw-semibold">Comprovante de pagamento</label>
                <input type="file" name="comprovante" id="comprovante" class="form-control" required accept=".jpg,.jpeg,.png,.pdf">
            </div>
            <button type="submit" class="btn btn-primary w-100">Enviar comprovante</button>
        </form>

        <?= $msg ?>
    </div>
</div>

</body>
</html>