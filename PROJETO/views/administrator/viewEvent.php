<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/cards.css" rel="stylesheet">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/sidebars.css">
    <link rel="stylesheet" href="css/main-content.css">
    <title>Acalento | Eventos</title>
</head>

<body>
<?php include(ROOT . "/components/sidebars/sidebar-mobile.php") ?>
<div class="d-flex flex-nowrap">
    <?php include(ROOT .  "/components/sidebars/sidebars.php") ?>
    <!-- fim sidebar -->
    <!-- conteudo -->
    <div class="main-content">
        <main class="px-5 row align-items-center">
            <div class="container-fluid">
                <div class="mb-3">
                    <!-- aqui vai o que você quer por -->
                    <h2>Eventos</h2>
                    <div class="row row-cols-1 row-cols-sm-1 row-cols-md-1 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-4 g-4 addScroll">
                    <?php
                    include (ROOT . "/php/config/database_php.php");
                    $conexao = connectDatabase();
                    $query = "SELECT evento.*,
                                     assentamento.nome AS assentamento_nome,
                                     endereco.rua,
                                     endereco.numero,
                                     endereco.bairro,
                                     (SELECT COUNT(*) FROM usuario_participa_evento WHERE id_evento = evento.id) AS inscritos
                              FROM evento
                              LEFT JOIN assentamento ON evento.id_assentamento = assentamento.id
                              LEFT JOIN endereco ON assentamento.id_endereco = endereco.id;";
                    $resultado = $conexao->query($query);
                    if (!$resultado) {
                        die("Erro na consulta: " . $conexao->error);
                    }

                    while ($linha = $resultado->fetch_object()) {
                    $data_formatada = date("d/m/Y", strtotime($linha->data));
                    $hora_formatada = date("H:i", strtotime($linha->hora));
                    ?>
                        <div class="col">
                        <div class="card h-100 amarelo" ">
                            <figure class="imagem-vertical degrade-vertical">
                                <img src="<?php echo $linha->link_imagem; ?>" class="card-img-top" alt="Imagem do evento">
                            </figure>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $linha->nome; ?></h5>
                                <p class="card-text">data: <?php echo $data_formatada; ?> às <?php echo $hora_formatada; ?></p>
                                <p class="card-text">local: <?php echo $linha->assentamento_nome; ?></p>
                                <p class="card-text"><?php echo $linha->rua;?>, <?php echo $linha->numero; ?> - <?php echo $linha->bairro; ?></p>
                                <p class="card-text">lotação: <?php echo $linha->inscritos; ?>/<?php echo $linha->lotacao_max; ?></p>
                                <p class="card-text"><small class="text-body-secondary"><?php echo $linha->descricao; ?></small></p>
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
