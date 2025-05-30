<?php
include_once (ROOT . '/components/progress-bar/progress-bar.php');
include_once (ROOT . "/components/sidebars/sidebars.php");
include_once (ROOT . '/php/config/session_php.php');
include_once (ROOT . '/php/config/database_php.php');
include_once (ROOT . '/php/auth_services/auth_service_php.php');
include_once (ROOT . '/php/handlers/form_validator_php.php');
include_once (ROOT . '/php/handlers/payment_handler.php');
include_once (ROOT . '/models/admin_models_php.php');
include_once (ROOT .  "/components/back/back.php");

$conn = connectDatabase();
load_user_session_data($conn);

// Obtém valor da doação
$valorBruto = $_SESSION['valor_doacao'] ?? null;
if (!$valorBruto) {
    exit;
}

$valorLimpo = str_replace(['R$', '.', ' '], '', $valorBruto);
$valor = (float) str_replace(',', '.', $valorLimpo);

$chave = '70230618600';
$nome = 'ANNA QUEZIA DOS SANTOS';
$cidade = 'CURITIBA';
$txid = uniqid();

$pixCode = geraPixCode($chave, $valor, $nome, $cidade, $txid);
$qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($pixCode);

// Upload do comprovante

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['comprovante'])) {
    $arquivo = validateFile('comprovante', 'comprovantes');

    if (!$arquivo) {
        showError(25);
    }

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
                    <div class="container" style="margin-bottom: 100px;">
                        <div class="card mx-auto p-4" style="max-width: 700px;">
                            <h2 class="text-center mb-3">Realize o pagamento</h2>
                            <p class="text-center text-muted">Valor: <strong>R$ <?= number_format($valor, 2, ',', '.') ?></strong></p>

                            <div class="text-center mb-4">
                                <img src="<?= $qrUrl ?>" alt="QR Code Pix" class="img-fluid" style="max-width: 200px;">
                            </div>

                            <div class="bg-body-secondary p-3 mb-4 text-center" style="background-color: var(--secondary-blue) !important; border-radius: 20px !important; border: 1px solid var(--primary-blue) !important;">
                                <p class="mb-1 fw-semibold">Copia e Cola:</p>
                                <textarea id="pixCopiaCola" class="form-control small" rows="3" readonly><?= $pixCode ?></textarea>
                                <button id="botaoCopiar" class="btn btn-primary mt-2" type="button" onclick="copiarPix()">Copiar</button>
                            </div>

                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="comprovante" class="form-label fw-semibold">Comprovante de pagamento</label>
                                    <input type="file" name="comprovante" id="comprovante" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                    <div id="validacaoComprovante" class="invalid-feedback">
                                        Envie um arquivo válido.
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Enviar comprovante</button>
                            </form>
                        </div>
                    </div>

                    <script>
                        function copiarPix() {
                            const textarea = document.getElementById('pixCopiaCola');
                            const botao = document.getElementById('botaoCopiar');
                            const texto = textarea.value;

                            navigator.clipboard.writeText(texto)
                                .then(() => {
                                    botao.classList.remove('btn-primary');
                                    botao.classList.add('btn-success');
                                    botao.innerHTML = 'Copiado com sucesso!';

                                    setTimeout(() => {
                                        botao.classList.remove('btn-success');
                                        botao.classList.add('btn-primary');
                                        botao.innerHTML = 'Copiar';
                                    }, 2000);
                                })
                                .catch(() => {
                                    alert('Falha ao copiar');
                                });
                        }
                    </script>

                </div>
            </div>
        </main>
    </div>
</div>
</body>