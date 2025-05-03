<?php
include (ROOT . "/php/config/database_php.php");
include(ROOT . "/components/sidebars/sidebars.php");
$conexao = connectDatabase();
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/form-style.css">
    <link rel="stylesheet" href="css/cards.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/main-content.css">
    <title>Acalento | Eventos</title>
</head>

<body>
    <?php make_mobile_sidebar() ?>
    <div class="d-flex flex-nowrap">
        <!--    monta a sidebar desktop-->
        <?php make_sidebar(); ?>
    <!-- fim sidebar -->
    <!-- conteudo -->
    <div class="main-content">
        <main class="px-5 row addScroll">
            <div class="container-fluid">
                <div class="mb-3">
                    <!-- aqui vai o que você quer por -->
                    <div class="doacoes">
                        <div class="d-flex justify-content-between">
                            <h2>Todas as doações</h2>
                            <a class="btn btn-primary" href="index.php?adm=9">Ver todas as doações</a>
                        </div>
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
                                LIMIT 5;";

                            $resultado = $conexao->query($query);

                            if (!$resultado) {
                                showError(7);
                            }

                            if ($resultado->num_rows <= 0) {
                                echo '<h3 class="d-flex justify-content-center p-5">Nenhuma doação encontrada</h3>';
                            } else { ?>
                                <table class="table table-hover table-amarela">
                                    <thead>
                                    <tr>
                                        <th scope="col">Item</th>
                                        <th scope="col">Quantidade</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Doador</th>
                                        <th scope="col">Data da doação</th>
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
                                    <td>' . $linha->quantidade . '</td>
                                    <td>' . $linha->tipo . '</td>
                                    <td>' . $linha->usuario_nome . '</td>
                                    <td>' . $data_formatada . '</td>
                                    <td>' . $destino . '</td>
                                </tr>';
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between">
                            <h2>Doações em estoque</h2>
                            <a class="btn btn-primary" href="index.php?adm=11">Ver todas as doações em estoque</a>
                        </div>
                            <?php
                            $query = "SELECT item.*,
                                      usuario.nome AS usuario_nome,
                                      opcao_item.nome AS opcao_nome
                                      FROM item
                                      LEFT JOIN usuario ON item.id_usuario = usuario.id
                                      LEFT JOIN opcao_item ON item.id_opcao = opcao_item.id
                                      WHERE item.id_estoque IS NOT NULL
                                      ORDER BY item.id DESC
                                      LIMIT 5";

                            $resultado = $conexao->query($query);

                            if (!$resultado) {
                                showError(7);
                            }

                            if ($resultado->num_rows <= 0) {
                                echo '<h3 class="d-flex justify-content-center p-5">Não há doações no estoque</h3>';
                            } else { ?>
                                <table class="table table-hover table-amarela">
                                    <thead>
                                    <tr>
                                        <th scope="col">Item</th>
                                        <th scope="col">Quantidade</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Doador</th>
                                        <th scope="col">Data da doação</th>
                                        <th scope="col">Destino</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                <?php while ($linha = $resultado->fetch_object()) {
                                    $data_formatada = date("d/m/Y", strtotime($linha->data));
                                    echo '
                                <tr>
                                    <td>'.$linha->opcao_nome.'</td>
                                    <td>'.$linha->quantidade.'</td>
                                    <td>'.$linha->tipo.'</td>
                                    <td>'.$linha->usuario_nome.'</td>
                                    <td>'.$data_formatada.'</td>
                                    <td>Estoque</td>
                                </tr>';
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between">
                            <h2>Doações por campanha</h2>
                            <a class="btn btn-primary" href="index.php?adm=8">Ver as doações por campanha</a>
                        </div>
                            <?php
                            $query = "SELECT campanha_doacao.*,
                            assentamento.nome AS assentamento_nome
                            FROM campanha_doacao
                            LEFT JOIN assentamento ON campanha_doacao.evento_destino = assentamento.id
                            ORDER BY campanha_doacao.id DESC
                            LIMIT 4;";
                            $resultado = $conexao->query($query);

                            if (!$resultado) {
                            showError(7);
                            }

                            if ($resultado->num_rows <= 0) {
                                echo '<h3 class="d-flex justify-content-center p-4">Nenhuma campanha cadastrada</h3>';
                            } else {?>
                        <div class="row row-cols-1 row-cols-sm-1 row-cols-md-1 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-4 g-4">
                         <?php
                        while ($linha = $resultado->fetch_object()) {
                                    $data_formatada = date("d/m/Y", strtotime($linha->data));
                                ?>
                            <div class="col">
                                <div class="card h-100 amarelo">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title"><?php echo $linha->nome; ?></h5>
                                        <p class="card-text">Data: <?php echo $data_formatada; ?></p>
                                        <p class="card-text">Local: <?php echo $linha->assentamento_nome; ?></p>
                                        <div class="mt-auto d-flex justify-content-between">
                                            <a href="index.php?adm=10&id=<?php echo $linha->id; ?>" class="btn btn-primary largura-completa">Visualizar doações</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                }
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

