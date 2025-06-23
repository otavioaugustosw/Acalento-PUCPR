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
$local = $_GET['settlement'] ?? null;
$hora = $_GET['time'] ?? null;

$nome_voluntario = $_SESSION['USER_NAME'] ?? "Voluntário";
$cpf = $_SESSION['USER_CPF'] ?? "Não informado";

if (!$nome_evento || !$data_evento) {
    die("Dados do evento não encontrados.");
}

$data_evento_formatada = date("d/m/Y", strtotime($data_evento));
$cpf_formatado = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $cpf);
$hora_formatada = date("H:i", strtotime($hora));
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Certificado de Participação</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Herr+Von+Muellerhoff&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
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
        <main class="px-5 row addScroll">
            <div class="container-fluid">
                <div class="d-flex align-items-center justify-content-between">
                    <h2>Certificado de Participação</h2>
                    <button onclick="window.print()" class="btn btn-primary">Imprimir Certificado</button>
                </div>

                <div class="certificado">
                    <div class="title d-flex align-items-center justify-content-center">
                        <img src="assets/imagens/award.svg" alt="award" width="100">
                        <h1>Certificado de Participação</h1>
                    </div>
                    <div class="content">
                        <p>A ONG Acalento certifica que</p>
                        <h4 class="fw-bold"><?= $nome_voluntario ?></h4>
                        <p>Portador(a) do CPF: <?= $cpf_formatado ?>, participou do evento <?= $nome_evento ?>, no dia <?= $data_evento_formatada ?>,<br>
                            no Assentamento <?= $local ?>, ás <?= $hora_formatada ?>, de forma voluntária,<br>
                            contribuindo com seu tempo, dedicação e esforço para o sucesso das atividades promovidas.</p>
                        <p>Agradecemos imensamente sua colaboração e empenho em prol da nossa causa.</p>
                    </div>
                    <div class="bottom">
                        <div class="bottom-content">
                            <div class="signed">Vitória Borges Lima</div>
                            <hr style="width: 250px; float: right;">
                            <p style="float: none; clear: both;">Assinatura</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>


