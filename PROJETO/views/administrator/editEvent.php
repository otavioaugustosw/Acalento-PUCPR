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
                    <h2>Eventos</h2>
                    <div class="row row-cols-1 row-cols-sm-1 row-cols-md-1 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-4 g-4">
                        <?php
                        include "../teste_conexao/conexao.php";

                        $conexao = conecta_db();
                        error_reporting(E_ALL);
                        ini_set('display_errors', 1);
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
                        error_reporting(E_ALL);
                        ini_set('display_errors', 1);

                        if (!$resultado) {
                            die("Erro na consulta: " . $conexao->error);
                        }

                        while ($linha = $resultado->fetch_object()) {
                            $data_formatada = date("d/m/Y", strtotime($linha->data));
                            $hora_formatada = date("H:i", strtotime($linha->hora));
                            ?>
                            <div class="col">
                                <div class="card h-100 amarelo mx-auto">
                                    <figure class="imagem-vertical degrade-vertical">
                                        <img src="<?php echo $linha->link_imagem; ?>" class="card-img-top" alt="Imagem do evento">
                                    </figure>
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title"><?php echo $linha->nome; ?></h5>
                                        <p class="card-text">Data: <?php echo $data_formatada ?> às <?php echo $hora_formatada; ?></p>
                                        <p class="card-text">local: <?php echo $linha->assentamento_nome; ?></p>
                                        <p class="card-text"><?php echo $linha->rua;?>, <?php echo $linha->numero; ?> - <?php echo $linha->bairro; ?></p>
                                        <p class="card-text">lotação: <?php echo $linha->inscritos ?>/<?php echo $linha->lotacao_max; ?></p>
                                        <p class="card-text"><small class="text-body-secondary"><?php echo $linha->descricao; ?></small></p>
                                        <div class="mt-auto d-flex justify-content-between gap-2">
                                            <a href="updateEvent.php?id=<?php echo $linha->id; ?>" class="btn btn-primary largura-50">Editar</a>
                                            <a href="deleteEvent.php?id=<?php echo $linha->id; ?>" class="btn btn-danger largura-50" onclick="return confirm('Tem certeza que deseja deletar')">Deletar</a>
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
