<?php
session_start();
session_destroy(); // limpa a sessão
session_start();

// Simula usuário logado (REMOVA quando tiver login real)
if (!isset($_SESSION['id_usuario'])) {
$_SESSION['id_usuario'] = 2; // ID de teste
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../PROJETO/components/cards/cards.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/default/default.css">
    <link rel="stylesheet" href="../../css/sidebars.css">
    <link rel="stylesheet" href="../../css/default/main-content.css">
    <title>Title</title>
</head>

<body>
<?php include ("../../../PROJETO/components/sidebars/sidebar-mobile.php") ?>
<div class="d-flex flex-nowrap">
    <?php include ("../../../PROJETO/components/sidebars/sidebars.php") ?>
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
                        $id_usuario = $_SESSION['id_usuario'];

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

                        // buscar eventos que o usuario está inscrito
                        $inscricao = $conexao->query("SELECT id_evento FROM usuario_participa_evento WHERE id_usuario = $id_usuario");
                        $eventos_inscritos = [];
                        while ($row = $inscricao->fetch_object()) {
                            $eventos_inscritos[] = $row->id_evento;
                        }

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
                                        <p class="card-text">Data: <?php echo $data_formatada; ?> às <?php echo $hora_formatada; ?></p>
                                        <p class="card-text">local: <?php echo $linha->assentamento_nome; ?></p>
                                        <p class="card-text"><?php echo $linha->rua;?>, <?php echo $linha->numero; ?> - <?php echo $linha->bairro; ?></p>
                                        <p class="card-text">lotação: <?php echo $linha->inscritos ?>/<?php echo $linha->lotacao_max; ?></p>
                                        <p class="card-text"><small class="text-body-secondary"><?php echo $linha->descricao; ?></small></p>
                                        <div class="mt-auto">
                                            <form action="<?= in_array($linha->id, $eventos_inscritos) ? 'cancelParticipation.php' : 'registerParticipation.php' ?>" method="POST">
                                                <input type="hidden" name="id_evento" value="<?= $linha->id ?>">
                                                <button type="submit" class="btn <?= in_array($linha->id, $eventos_inscritos) ? 'btn-danger' : 'btn-primary' ?> largura-completa">
                                                    <?= in_array($linha->id, $eventos_inscritos) ? 'Cancelar inscrição' : 'Inscrever-se' ?>
                                                </button>
                                            </form>
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
<!-- Toasts de feedback -->
<?php if (isset($_GET['sucesso'])): ?>
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div class="toast text-bg-success show" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <?php
                    if ($_GET['sucesso'] === 'inscrito') echo "Inscrição realizada com sucesso!";
                    elseif ($_GET['sucesso'] === 'cancelado') echo "Inscrição cancelada com sucesso!";
                    ?>
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
<?php elseif (isset($_GET['erro'])): ?>
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div class="toast text-bg-danger show" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <?php
                    switch ($_GET['erro']) {
                        case 'evento_lotado': echo "Este evento está lotado."; break;
                        case 'erro_ao_inscrever': echo "Erro ao se inscrever."; break;
                        case 'erro_ao_cancelar': echo "Erro ao cancelar inscrição."; break;
                        default: echo "Erro desconhecido.";
                    }
                    ?>
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
