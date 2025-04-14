<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../components/cards/cards.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/default/default.css">
    <link rel="stylesheet" href="../../css/sidebars.css">
    <link rel="stylesheet" href="../../css/default/main-content.css">
    <title>Title</title>
</head>

<body>
<?php include("../../../PROJETO/components/sidebars/sidebar-mobile.php") ?>
<div class="d-flex flex-nowrap">
    <?php include("../../../PROJETO/components/sidebars/sidebars.php") ?>
    <!-- fim sidebar -->

    <!-- conteudo -->
    <div class="main-content">
        <main class="px-5 row align-items-center">
            <div class="container-fluid">
                <div class="mb-3">
                    <!-- aqui vai o que você quer por -->
                    <h2>Campanhas</h2>
                    <div class="row row-cols-1 row-cols-sm-1 row-cols-md-1 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-4 g-4">
                        <?php
                        include "../teste_conexao/conexao.php";

                        $conexao = conecta_db();
                        error_reporting(E_ALL);
                        ini_set('display_errors', 1);
                        $query = "SELECT campanha_doacao.*,
                                     assentamento.nome AS assentamento_nome
                              FROM campanha_doacao
                              LEFT JOIN assentamento ON campanha_doacao.evento_destino = assentamento.id;";
                        $resultado = $conexao->query($query);
                        error_reporting(E_ALL);
                        ini_set('display_errors', 1);

                        if (!$resultado) {
                            die("Erro na consulta: " . $conexao->error);
                        }

                        while ($linha = $resultado->fetch_object()) {

                        ?>
                        <div class="col">
                            <div class="card h-100 amarelo">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?php echo $linha->nome; ?></h5>
                                <p class="card-text">Data: <?php echo $linha->data; ?></p>
                                <p class="card-text">Local: <?php echo $linha->assentamento_nome; ?></p>
                                <div class="mt-auto d-flex justify-content-between">
                                    <a href="viewDonation.php?id=<?php echo $linha->id; ?>" class="btn btn-primary largura-completa">Visualizar doações</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                </div>
                <!-- aqui termina -->
            </div>
    </div>
    </main>
</div>
</div>
</body>
</html>

