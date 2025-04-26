<?php
include (ROOT . "/php/config/database_php.php");
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
    <link rel="stylesheet" href="css/cards.css">
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
        <main class="px-5 row addScroll">
            <div class="container-fluid">
                <div class="mb-3">
                    <h2>Estoque</h2>
                    <?php
                    include(ROOT . "/components/filter/filter.php");
                    include(ROOT . "/php/handlers/filter.php");
                    $where = setWhere('item');
                    $query = "SELECT item.*,
                                      usuario.nome AS usuario_nome,
                                      opcao_item.nome AS opcao_nome
                                      FROM item
                                      LEFT JOIN usuario ON item.id_usuario = usuario.id
                                      LEFT JOIN opcao_item ON item.id_opcao = opcao_item.id
                                      $where AND item.id_estoque IS NOT NULL";

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
            </div>
        </main>
    </div>
</div>
</body>
</html>