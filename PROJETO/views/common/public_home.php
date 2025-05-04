<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acalento | Início</title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/main-content.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<!-- Header fixo -->
<?php include(ROOT . "/components/header/header.php");?>

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

    <!-- IMAGEM PARA O LADO ESQUERDO -->
    <section class="text-image left">
        <div class="container d-flex align-items-center gap-4 flex-row-reverse">
            <img src="assets/imagens/teste2.jpg" alt="Imagem 2" class="img-fluid">
            <article>
                <h2>Doar também é</h2>
                <p>Doar é também dizer: você importa. É um gesto silencioso que grita cuidado, que constrói futuros e devolve dignidade. Cada doação, grande ou pequena, é uma semente de mudança no coração de quem mais precisa.</p>
            </article>
        </div>
    </section>
</main>
</body>
</html>