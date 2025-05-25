<?php
include_once (ROOT . "/components/cards/cards.php");
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
    <link rel="stylesheet" href="css/thank_you.css">
    <link rel="stylesheet" href="css/card-reveal.css">
</head>

<body>

<!-- Header fixo -->
<?php include_once (ROOT . "/components/header/header.php");?>

<!-- Conteúdo principal -->
<main class="image-text-column" style="margin-top: 50px;">
    <figure>
        <img src="assets/imagens/teste6.jpeg">
    </figure>
    <article>
        <p>
            Você acaba de fazer algo muito especial — e queremos que saiba o quanto isso significa pra gente.
            Sua contribuição é mais do que um valor: é um gesto de empatia, solidariedade e carinho. </p>
        <p>
            De todo o coração, o nosso muito obrigada <strong><?= $_SESSION['USER_NAME'] ?>!</strong>
        </p>
        <p>Esperamos que esse seja apenas o começo de uma linda jornada ao nosso lado.</p>
    </article>
    <div class="link-container">
        <a href="index.php?common=6" class="btn btn-primary">Ir para a área do doador</a>
    </div>
</main>
</body>
</html>