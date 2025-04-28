<?php
include (ROOT . "/php/config/database_php.php");
$conexao = connectDatabase();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/sidebars.css">
    <link rel="stylesheet" href="css/main-content.css">
    <link rel="stylesheet" href="css/cards.css">
    <title>Acalento | Eventos</title>
</head>

<body>
<?php include(ROOT . "/components/sidebars/sidebar-mobile.php") ?>
<div class="d-flex flex-nowrap">
    <?php include(ROOT .  "/components/sidebars/sidebars.php") ?>
    <!-- fim sidebar -->
    <!-- conteudo -->
    <div class="main-content flex-grow-1">
        <main class="px-5 row addScroll py-5">
            <div class="container-fluid">
                <div class="mb-3">
                    <div class="row">
                        <?php if(!$_SESSION['USER_IS_DONATOR'] && !$_SESSION['USER_IS_ADMINISTRATOR']) { ?>
                        <div class="col-md-6 px-4">
                            <h2>Próximos eventos</h2>
                            <?php
                            $id_usuario = $_SESSION['USER_ID'];
                            $query = "SELECT evento.*,
                                     assentamento.nome AS assentamento_nome,
                                     endereco.rua,
                                     endereco.numero,
                                     endereco.bairro,
                                     (SELECT COUNT(*) FROM usuario_participa_evento WHERE id_evento = evento.id) AS inscritos
                                      FROM evento
                                      LEFT JOIN assentamento ON evento.id_assentamento = assentamento.id
                                      LEFT JOIN endereco ON assentamento.id_endereco = endereco.id
                                      WHERE evento.data >= NOW() AND evento.status = 0 
                                      ORDER BY evento.id DESC
                                      LIMIT 2;";

                            $resultado = $conexao->query($query);
                            // buscar eventos que o usuario está inscrito
                            $inscricao = $conexao->query("SELECT id_evento FROM usuario_participa_evento WHERE id_usuario = $id_usuario");
                            $eventos_inscritos = [];
                            while ($row = $inscricao->fetch_object()) {
                                $eventos_inscritos[] = $row->id_evento;
                            }

                            if (!$resultado) {
                                showError(7);
                            }

                            if ($resultado->num_rows <= 0) {
                                echo '<h3>Nenhum evento cadastrado</h3>';
                            } else {
                                while ($linha = $resultado->fetch_object()) {
                                    $data_formatada = date("d/m/Y", strtotime($linha->data));
                                    $hora_formatada = date("H:i", strtotime($linha->hora));
                                    ?>
                            <div class="card mb-3 amarelo mx-auto" >
                                <div class="row g-0">
                                    <div class="col-md-4 degrade-horizontal">
                                        <figure class="imagem-horizontal v-20">
                                            <img src="<?=$linha->link_imagem == "" || !isset($linha->link_imagem) ? "assets/imagens/default.jpg" : $linha->link_imagem?>" class="img-fluid rounded-start" alt="...">
                                        </figure>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title"><?= $linha->nome ?></h5>
                                            <p class="card-text"><?= $data_formatada ?> às <?= $hora_formatada?></p>
                                            <p class="card-text"><?= $linha-> assentamento_nome?></p>
                                            <p class="card-text"><?= $linha->rua;?>, <?= $linha->numero; ?> - <?= $linha->bairro; ?></p>
                                            <p class="card-text">lotação: <?= $linha->inscritos ?>/<?= $linha->lotacao_max; ?></p>
                                            <p class="card-text"><small class="text-body-secondary"><?= $linha->descricao; ?></small></p>
                                            <div class="mt-auto">
                                                <?php if (in_array($linha->id, $eventos_inscritos)) { ?>
                                                    <button type="button" class="btn btn-danger largura-completa" data-bs-toggle="modal" data-bs-target="#modalCancelar<?= $linha->id ?>">
                                                        Cancelar inscrição
                                                    </button>
                                                    <div class="modal fade" id="modalCancelar<?= $linha->id ?>" tabindex="-1" aria-labelledby="cancelarLabel<?= $linha->id ?>" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content amarelo">
                                                                <form action="index.php?voluntary=1" method="POST">
                                                                    <input type="hidden" name="id_evento" value="<?= $linha->id ?>">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="cancelarLabel<?= $linha->id ?>">Confirmar cancelamento</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Tem certeza que deseja cancelar sua inscrição neste evento?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não, voltar</button>
                                                                        <button type="submit" class="btn btn-danger">Sim, cancelar</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } elseif ($linha->inscritos >= $linha->lotacao_max) { ?>
                                                    <input type="hidden" name="id_evento" value="<?= $linha->id ?>">
                                                    <button type="button" class="btn btn-secondary largura-completa">
                                                        Evento lotado
                                                    </button>
                                                <?php } else { ?>
                                                    <form action="index.php?voluntary=3" method="POST">
                                                        <input type="hidden" name="id_evento" value="<?= $linha->id ?>">
                                                        <button type="submit" class="btn btn-primary largura-completa">
                                                            Inscrever-se
                                                        </button>
                                                    </form>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php }
                                } ?>
                        </div>

                        <div class="col-md-6 px-4">
                            <h2>Últimos eventos</h2>
                            <?php

                            $id_usuario = $_SESSION['USER_ID'];

                            $query = "SELECT evento.*,
                                     assentamento.nome AS assentamento_nome,
                                     endereco.rua,
                                     endereco.numero,
                                     endereco.bairro,
                                     (SELECT COUNT(*) FROM usuario_participa_evento WHERE id_evento = evento.id) AS inscritos
                                      FROM evento
                                      LEFT JOIN assentamento ON evento.id_assentamento = assentamento.id
                                      LEFT JOIN endereco ON assentamento.id_endereco = endereco.id
                                      WHERE evento.data < NOW() AND evento.status = 0 
                                      LIMIT 2;";
                            $resultado = $conexao->query($query);

                            if (!$resultado) {
                                showError(7);
                            }

                            if ($resultado->num_rows <= 0) {
                                echo '<h3>Nenhum evento cadastrado</h3>';
                            } else {
                                while ($linha = $resultado->fetch_object()) {
                                    $data_formatada = date("d/m/Y", strtotime($linha->data));
                                    $hora_formatada = date("H:i", strtotime($linha->hora));
                                    ?>
                                    <div class="card mb-3 amarelo mx-auto" >
                                        <div class="row g-0">
                                            <div class="col-md-4 degrade-horizontal">
                                                <figure class="imagem-horizontal">
                                                    <img src="<?=$linha->link_imagem == "" || !isset($linha->link_imagem) ? "assets/imagens/default.jpg" : $linha->link_imagem?>" class="img-fluid rounded-start" alt="...">
                                                </figure>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body d-flex flex-column ">
                                                    <h5 class="card-title"><?= $linha->nome ?></h5>
                                                    <p class="card-text"><?= $data_formatada ?> às <?= $hora_formatada?></p>
                                                    <p class="card-text"><?= $linha-> assentamento_nome?></p>
                                                    <p class="card-text"><?= $linha->rua;?>, <?= $linha->numero; ?> - <?= $linha->bairro; ?></p>
                                                    <p class="card-text">lotação: <?= $linha->inscritos ?>/<?= $linha->lotacao_max; ?></p>
                                                    <p class="card-text"><small class="text-body-secondary"><?= $linha->descricao; ?></small></p>
                                                    <div class="mt-auto">
                                                        <button type="button" class="btn btn-secondary largura-completa" disabled>Evento encerrado</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            } ?>
                        </div>

                        <?php } ?>

                        <?php if (!$_SESSION['USER_IS_ADMINISTRATOR']) { ?>
                        <!-- só voluntário -->
                        <div class="col-md-6 px-4">
                            <h2>Suas últimas doações</h2>
                            <?php
                            $query = "SELECT item.*,
                                usuario.nome AS usuario_nome,
                                opcao_item.nome AS opcao_nome,
                                campanha_doacao.nome AS campanha_doacao_nome
                                FROM item
                                LEFT JOIN usuario ON item.id_usuario = usuario.id
                                LEFT JOIN opcao_item ON item.id_opcao = opcao_item.id
                                LEFT JOIN campanha_doacao ON item.id_campanha_doacao = campanha_doacao.id
                                WHERE id_usuario = {$_SESSION['USER_ID']};";

                            $resultado = $conexao->query($query);

                            if (!$resultado) {
                                showError(7);
                            }

                            if ($resultado->num_rows <= 0) {
                                echo '<h3 class="p-5">Nenhuma doação encontrada.</h3>';
                            } else { ?>
                            <table class="table table-hover table-amarela">
                                <thead>
                                <tr>
                                    <th scope="col">Item</th>
                                    <th scope="col">Data da doação</th>
                                    <th scope="col">Destino</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                while ($linha = $resultado->fetch_object()) {
                                    $destino = $linha->campanha_doacao_nome ? $linha->campanha_doacao_nome : 'Estoque';
                                    $data_formatada = date("d/m/Y", strtotime($linha->data));
                                    echo '
                            <tr>
                                <td>' . $linha->opcao_nome . '</td>
                                <td>' . $data_formatada . '</td>
                                <td>' . $destino . '</td>
                            </tr>';
                                }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-6 px-4">
                            <h2>Próximos eventos</h2>
                            <?php
                            $id_usuario = $_SESSION['USER_ID'];
                            $query = "SELECT evento.*,
                                     assentamento.nome AS assentamento_nome,
                                     endereco.rua,
                                     endereco.numero,
                                     endereco.bairro,
                                     (SELECT COUNT(*) FROM usuario_participa_evento WHERE id_evento = evento.id) AS inscritos
                                      FROM evento
                                      LEFT JOIN assentamento ON evento.id_assentamento = assentamento.id
                                      LEFT JOIN endereco ON assentamento.id_endereco = endereco.id
                                       WHERE evento.data >= NOW() AND evento.status = 0
                                      ORDER BY evento.id DESC
                                      LIMIT 2;";

                            $resultado = $conexao->query($query);
                            // buscar eventos que o usuario está inscrito
                            $inscricao = $conexao->query("SELECT id_evento FROM usuario_participa_evento WHERE id_usuario = $id_usuario");
                            $eventos_inscritos = [];
                            while ($row = $inscricao->fetch_object()) {
                                $eventos_inscritos[] = $row->id_evento;
                            }

                            if (!$resultado) {
                                showError(7);
                            }

                            if ($resultado->num_rows <= 0) {
                                echo '<h3>Nenhum evento cadastrado</h3>';
                            } else {
                                while ($linha = $resultado->fetch_object()) {
                                    $data_formatada = date("d/m/Y", strtotime($linha->data));
                                    $hora_formatada = date("H:i", strtotime($linha->hora));
                                    ?>
                                    <div class="card mb-3 amarelo mx-auto" >
                                        <div class="row g-0">
                                            <div class="col-md-4 degrade-horizontal">
                                                <figure class="imagem-horizontal">
                                                    <img src="<?=$linha->link_imagem == "" || !isset($linha->link_imagem) ? "assets/imagens/default.jpg" : $linha->link_imagem?>" class="img-fluid rounded-start" alt="...">
                                                </figure>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body d-flex flex-column ">
                                                    <h5 class="card-title"><?= $linha->nome ?></h5>
                                                    <p class="card-text"><?= $data_formatada ?> às <?= $hora_formatada?></p>
                                                    <p class="card-text"><?= $linha-> assentamento_nome?></p>
                                                    <p class="card-text"><?= $linha->rua;?>, <?= $linha->numero; ?> - <?= $linha->bairro; ?></p>
                                                    <p class="card-text">lotação: <?= $linha->inscritos ?>/<?= $linha->lotacao_max; ?></p>
                                                    <p class="card-text"><small class="text-body-secondary"><?= $linha->descricao; ?></small></p>
                                                    <div class="mt-auto">
                                                        <?php if (in_array($linha->id, $eventos_inscritos)) { ?>
                                                            <button type="button" class="btn btn-danger largura-completa" data-bs-toggle="modal" data-bs-target="#modalCancelar<?= $linha->id ?>">
                                                                Cancelar inscrição
                                                            </button>
                                                            <div class="modal fade" id="modalCancelar<?= $linha->id ?>" tabindex="-1" aria-labelledby="cancelarLabel<?= $linha->id ?>" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content amarelo">
                                                                        <form action="index.php?voluntary=1" method="POST">
                                                                            <input type="hidden" name="id_evento" value="<?= $linha->id ?>">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="cancelarLabel<?= $linha->id ?>">Confirmar cancelamento</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                Tem certeza que deseja cancelar sua inscrição neste evento?
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não, voltar</button>
                                                                                <button type="submit" class="btn btn-danger">Sim, cancelar</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } elseif ($linha->inscritos >= $linha->lotacao_max) { ?>
                                                            <input type="hidden" name="id_evento" value="<?= $linha->id ?>">
                                                            <button type="button" class="btn btn-secondary largura-completa">
                                                                Evento lotado
                                                            </button>
                                                        <?php } else { ?>
                                                            <form action="index.php?voluntary=3" method="POST">
                                                                <input type="hidden" name="id_evento" value="<?= $linha->id ?>">
                                                                <button type="submit" class="btn btn-primary largura-completa">
                                                                    Inscrever-se
                                                                </button>
                                                            </form>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            } ?>
                        </div>

                        <?php } ?>

                        <?php if ($_SESSION['USER_IS_ADMINISTRATOR']) { ?>
                        <div class="col-md-6">
                            <h2>Últimas doações recebidas</h2>
                            <?php
                            $query = "SELECT item.*,
                                usuario.nome AS usuario_nome,
                                opcao_item.nome AS opcao_nome,
                                campanha_doacao.nome AS campanha_doacao_nome
                                FROM item
                                LEFT JOIN usuario ON item.id_usuario = usuario.id
                                LEFT JOIN opcao_item ON item.id_opcao = opcao_item.id
                                LEFT JOIN campanha_doacao ON item.id_campanha_doacao = campanha_doacao.id
                                ORDER BY item.id DESC
                                LIMIT 10;";

                            $resultado = $conexao->query($query);

                            if (!$resultado) {
                                showError(7);
                            }

                            if ($resultado->num_rows <= 0) {
                                echo '<h3 class="d-flex justify-content-center p-5">Nenhuma doação encontrada</h3>';
                            } else { ?>
                            <div class="px-4">
                                <table class="table table-hover table-amarela">
                                    <thead>
                                    <tr>
                                        <th scope="col">Item</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Doador</th>
                                        <th scope="col">Destino</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php while ($linha = $resultado->fetch_object()) {
                                        $destino = $linha->campanha_doacao_nome ? $linha->campanha_doacao_nome : 'Estoque';
                                        $data_formatada = date("d/m/Y", strtotime($linha->data));
                                        echo '
                                    <tr>
                                        <td>' . $linha->opcao_nome . '</td>
                                        <td>' . $linha->tipo . '</td>
                                        <td>' . $linha->usuario_nome . '</td>
                                        <td>' . $destino . '</td>
                                    </tr>';
                                    }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-6 px-4">
                            <h2>Últimos eventos cadastrados</h2>
                            <?php
                            $conexao = connectDatabase();
                            $query = "SELECT evento.*,
                            assentamento.nome AS assentamento_nome,
                            endereco.rua,
                            endereco.numero,
                            endereco.bairro,
                            (SELECT COUNT(*) FROM usuario_participa_evento WHERE id_evento = evento.id) AS inscritos
                            FROM evento
                            LEFT JOIN assentamento ON evento.id_assentamento = assentamento.id
                            LEFT JOIN endereco ON assentamento.id_endereco = endereco.id
                            ORDER BY evento.id DESC
                            LIMIT 2;";

                            if (!$resultado) {
                            showError(7);
                            }

                            if ($resultado->num_rows <= 0) {
                            echo '<h3>Nenhum evento encontrado</h3>';
                            } else {
                            while ($linha = $resultado->fetch_object()) {
                            $data_formatada = date("d/m/Y", strtotime($linha->data));
                            $hora_formatada = date("H:i", strtotime($linha->hora));
                            ?>
                            <div class="col">
                                <div class="card mb-3 amarelo mx-auto" >
                                    <div class="row g-0">
                                        <div class="col-md-4 degrade-horizontal">
                                            <figure class="imagem-horizontal">
                                                <img src="<?=$linha->link_imagem == "" || !isset($linha->link_imagem) ? "assets/imagens/default.jpg" : $linha->link_imagem?>" class="img-fluid rounded-start" alt="...">
                                            </figure>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body d-flex flex-column ">
                                                <h5 class="card-title"><?= $linha->nome ?></h5>
                                                <p class="card-text"><?= $data_formatada ?> às <?= $hora_formatada?></p>
                                                <p class="card-text"><?= $linha-> assentamento_nome?></p>
                                                <p class="card-text"><?= $linha->rua;?>, <?= $linha->numero; ?> - <?= $linha->bairro; ?></p>
                                                <p class="card-text">lotação: <?= $linha->inscritos ?>/<?= $linha->lotacao_max; ?></p>
                                                <p class="card-text"><small class="text-body-secondary"><?= $linha->descricao; ?></small></p>
                                        <div class="mt-auto d-flex justify-content-between gap-2">
                                            <a href="index.php?adm=6&id=<?php echo $linha->id; ?>" class="btn btn-primary largura-50">Editar</a>
                                            <button type="button" class="btn btn-danger largura-50" data-bs-toggle="modal" data-bs-target="#modalDeletar<?= $linha->id ?>">
                                                Deletar
                                            </button>
                                        </div>
                                        <div class="modal fade" id="modalDeletar<?= $linha->id ?>" tabindex="-1" aria-labelledby="modalDeletarLabel<?= $linha->id ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content amarelo">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalDeletarLabel<?= $linha->id ?>">Confirmar exclusão</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Tem certeza que deseja deletar esse evento?
                                                    </div>
                                                    <div class="modal-footer d-flex">
                                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancelar</button>
                                                        <a href="index.php?adm=4&id=<?= $linha->id ?>" class="btn btn-danger">Sim, deletar</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                <?php
                                }
                                }
                                }
                                ?>


                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
