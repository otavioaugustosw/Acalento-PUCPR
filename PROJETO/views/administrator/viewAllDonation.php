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
    <title>Acalento | Todas as Doações</title>
</head>

<body>
<?php include(ROOT . "/components/sidebars/sidebar-mobile.php") ?>
<div class="d-flex flex-nowrap">
    <?php include(ROOT .  "/components/sidebars/sidebars.php") ?>

    <!-- conteudo -->
    <div class="main-content">
        <main class="px-5 row">
            <div class="container-fluid">
                <div class="mb-3">
                    <h2>Itens</h2>
                    <table class="table table-hover table-amarela">
                        <thead>
                        <tr>
                            <th scope="col">Item</th>
                            <th scope="col">Quantidade</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Doador</th>
                            <th scope="col">Destino</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $query = "SELECT item.*,
                                usuario.nome AS usuario_nome,
                                opcao_item.nome AS opcao_nome,
                                campanha_doacao.nome AS campanha_doacao_nome
                                FROM item
                                LEFT JOIN usuario ON item.id_usuario = usuario.id
                                LEFT JOIN opcao_item ON item.id_opcao = opcao_item.id
                                LEFT JOIN campanha_doacao ON item.id_campanha_doacao = campanha_doacao.id;";

                        $resultado = $conexao->query($query);

                        if (!$resultado) {
                            die("Erro na consulta: " . $conexao->error);
                        }

                        while ($linha = $resultado->fetch_object()) {
                            echo '
                            <tr>
                                <td>'.$linha->opcao_nome.'</td>
                                <td>'.$linha->quantidade.'</td>
                                <td>'.$linha->tipo.'</td>
                                <td>'.$linha->usuario_nome.'</td>
                                <td>'.$linha->campanha_doacao_nome.'</td>
                            </tr>';
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

