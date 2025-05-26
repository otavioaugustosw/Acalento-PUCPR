<?php
// includes e conexão
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . "/php/handlers/filter_php.php");
include_once (ROOT . "/components/filter/filter.php");
include_once (ROOT . "/components/cards/cards.php");
include_once (ROOT .  "/components/sidebars/sidebars.php");
include_once (ROOT .  "/models/voluntary_models_php.php");
include_once (ROOT .  "/php/handlers/error_handler_php.php");

$conn = connectDatabase();

$nome_evento = $_GET['nome_evento'] ?? null;
$data_evento = $_GET['date'] ?? null;
$nome_voluntario = $_SESSION['USER_NAME'] ?? "Voluntário";

if (!$nome_evento || !$data_evento) {
    die("Dados do evento não encontrados.");
}

$data_evento_formatada = date("d/m/Y", strtotime($data_evento));
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Certificado de Participação</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/main-content.css">
    <link rel="stylesheet" href="css/form-style.css">
    <link rel="stylesheet" href="css/cards.css">
    <link rel="stylesheet" href="css/certificate.css">

</head>
<body>
<?php make_mobile_sidebar() ?>
<div class="d-flex flex-nowrap">
    <?php make_sidebar(); ?>
    <div class="main-content">
        <main class="px-5 row">
            <div class="container-fluid">
                <h2>Certificado de Participação</h2>
                <div class="certificado">
                    <h1>Certificado de Participação</h1>
                    <p>
                        Certificamos que <strong><?= $nome_voluntario ?></strong><br>
                        participou como voluntário no evento <strong><?= $nome_evento ?></strong><br>
                        realizado no dia <strong><?= $data_evento_formatada ?></strong>.
                    </p>
                    <p>
                        Agradecemos pela sua contribuição e dedicação.
                    </p>
                </div>
                <button onclick="window.print()" class="btn btn-primary">Imprimir Certificado</button>

            </div>
        </main>
    </div>
</div>
</body>
</html>


