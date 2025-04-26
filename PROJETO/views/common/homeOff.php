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
<?php include (ROOT . "/views/common/header.php");?>

<!-- Conteúdo principal -->
<main style="margin-top: 120px;">  <!-- margem para descolar do header -->

    <!-- IMAGEM PARA O LADO DIREITO -->
    <section class="text-image right">
        <div class="container d-flex align-items-center gap-4">
            <img src="img/img1.png" alt="Imagem 1" class="img-fluid">
            <article>
                <h2>Amar é acalento</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras nec magna ac metus gravida tempus. Mauris
                    vel vestibulum urna. Suspendisse fermentum ut ante luctus consequat...</p>
                <a href="#">Conheça nossas atuações</a>
            </article>
        </div>
    </section>

    <!-- IMAGEM PARA O LADO ESQUERDO -->
    <section class="text-image left">
        <div class="container d-flex align-items-center gap-4 flex-row-reverse">
            <img src="img/img2.jpeg" alt="Imagem 2" class="img-fluid">
            <article>
                <h2>Amar é acalento</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras nec magna ac metus gravida tempus. Mauris
                    vel vestibulum urna. Suspendisse fermentum ut ante luctus consequat...</p>
                <a href="#">Conheça nossas atuações</a>
            </article>
        </div>
    </section>

</main>

</body>
</html>