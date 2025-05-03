<?php
include(ROOT . "/php/config/database_php.php");
include(ROOT . "/php/handlers/filter.php");
include(ROOT . "/components/filter/filter.php");
include(ROOT . "/components/cards/cards.php");
include(ROOT . "/components/modal/modal.php");
include(ROOT .  "/components/sidebars/sidebars.php");

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/main-content.css">
    <link rel="stylesheet" href="css/cards.css">
    <link rel="stylesheet" href="css/form-style.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <title>Acalento | Editar evento</title>
</head>

<body>
<!-- monta a sidebar mobile -->
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
                    <h2>Eventos</h2>
                    <?php makeFilter();?>
                    <div class="row row-cols-1 row-cols-sm-1 row-cols-md-1 row-cols-lg-2 row-cols-xl-2 row-cols-xxl-3 g-5 main">
                        <?php
                        $conexao = connectDatabase();

                        $where = setWhere('evento');
                        $query = "SELECT evento.*,
                                     assentamento.nome AS assentamento_nome,
                                     endereco.rua,
                                     endereco.numero,
                                     endereco.bairro,
                                     (SELECT COUNT(*) FROM usuario_participa_evento WHERE id_evento = evento.id) AS inscritos
                              FROM evento
                              LEFT JOIN assentamento ON evento.id_assentamento = assentamento.id
                              LEFT JOIN endereco ON assentamento.id_endereco = endereco.id
                              $where;";
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

                                // aqui é onde construimos os botões que vai pro card, em uma closure
                                $botoes = function () use ($linha) {
                                    makeButton("Editar", "btn btn-primary", "index.php?adm=6&id=$linha->id");
                                    makeModal($linha->id, button_text: 'Deletar',
                                        modal_title: 'Confirmar exclusão',
                                        modal_body: 'Tem certeza que deseja deletar esse evento',
                                        cancel_text: "Cancelar",
                                        confirm_text: 'Sim, deletar',
                                        form_action: "index.php?adm=4&id=$linha->id");
                                };
                                // montamos o card a nossa maneira
                                make_vertical_card(
                                    $linha->nome,
                                    "$data_formatada às $hora_formatada",
                                    $linha->assentamento_nome,
                                    "$linha->inscritos/$linha->lotacao_max inscritos",
                                    $linha->descricao,
                                    $linha->link_imagem,
                                    $botoes
                                );
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
