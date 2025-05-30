<?php
include_once (ROOT . "/components/cards/cards.php");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acalento | Início</title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/main-content.css">
    <link rel="stylesheet" href="css/card-reveal.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<!-- Header fixo -->
<?php include_once (ROOT . "/components/header/header.php");?>

<!-- Conteúdo principal -->
<main style="margin-top: 120px;">  <!-- margem para descolar do header -->

    <!-- IMAGEM PARA O LADO DIREITO -->
    <section class="text-image right">
        <div class="container d-flex align-items-center gap-4">
            <img src="assets/imagens/teste1.jpeg" alt="Imagem 1" class="img-fluid">
            <article>
                <h2>Amar é acalento</h2>
                <p>Amar é envolver uma criança com o calor da esperança. É enxergar além da dor, é estender as mãos quando o mundo parece frio. No Acalento, acreditamos que amor é mais do que sentir — é agir, é proteger, é transformar vidas com carinho e presença</p>
            </article>
        </div>
    </section>
    <div class="container">
        <div class="mb-3">
            <div class="mt-5 mb-4">
                <h2>Seja um Apoiador Acalento!</h2>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <?php make_event_card_reveal("Doação Material", "Doe itens e transforme recursos em esperança 
                    para quem mais precisa!", "Doe Agora!", "index.php?common=2", "btn btn-primary 
                    largura-completa", "assets/imagens/teste3.jpeg");?>
                </div>
                <div class="col-md-4">
                    <?php make_event_card_reveal("Seja voluntário", "Ofereça seu tempo e ajude a construir um 
                    amanhã mais justo para todos!", "Seja voluntário!", "#", "btn btn-primary 
                    largura-completa", "assets/imagens/teste5.jpeg");?>
                </div>
                <?php if(isset($_SESSION['USER_NAME'])) { ?>
                    <div class="col-md-4">
                        <?php make_event_card_reveal("Doação Monetária", "Faça a sua doação e ajude centenas de 
                        crianças a terem um futuro melhor!", "Doe Agora!", "index.php?common=12", "btn btn-primary 
                        largura-completa", "assets/imagens/teste4.jpeg");?>
                    </div>
                <?php } else { ?>
                    <div class="col-md-4">
                        <?php make_event_card_reveal("Doação Monetária", "Faça a sua doação e ajude centenas de 
                        crianças a terem um futuro melhor!", "Doe Agora!", "index.php?common=11", "btn btn-primary 
                        largura-completa", "assets/imagens/teste4.jpeg");?>
                    </div>
                <?php } ?>
            </div>
</main>
</body>
</html>