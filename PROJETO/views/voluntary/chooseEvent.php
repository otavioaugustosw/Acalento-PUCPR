<?php
// todos includes e queries sempre no cabeçalho do código
include(ROOT . "/php/config/database_php.php");
include(ROOT . "/php/handlers/filter.php");
include(ROOT . "/components/filter/filter.php");
include(ROOT . "/components/cards/cards.php");
include(ROOT . "/components/modal/modal.php");
include(ROOT .  "/components/sidebars/sidebars.php");

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
        $where
  ";
$resultado = $conexao->query($query);
// busca eventos que o usuario está inscrito
$inscricao = $conexao->query("SELECT id_evento FROM usuario_participa_evento WHERE id_usuario =" . $_SESSION['USER_ID']);
$eventos_inscritos = [];
// cada row recebe o valor de um id do evento e eventos inscritos recebe os id dos eventos que o usuário está escrito
while ($row = $inscricao->fetch_object()) {
    $eventos_inscritos[] = $row->id_evento;
}
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
    <title>Acalento | Eventos</title>
</head>
<body>
<!-- monta a sidebar mobile -->
<?php make_mobile_sidebar() ?>
<div class="d-flex flex-nowrap">
<!--    monta a sidebar desktop-->
    <?php make_sidebar(); ?>
    <div class="main-content">
        <main class="px-5 row addScroll">
            <div class="container-fluid">
                <div class="mb-3">
                    <h2>Eventos</h2>
<!--                    monta o filtro com botão e campo de selecionar -->
                    <?php makeFilter() ?>
                    <div class="row row-cols-1 row-cols-sm-1 row-cols-md-1 row-cols-lg-2 row-cols-xl-2 row-cols-xxl-3 g-5 main">
                        <?php
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
                                $botoes = function () use ($linha, $eventos_inscritos) {
                                    //  Se o valor do id existir em eventos inscritos aparece a opção de cancelar inscrição,
                                    // caso não tenha aparece a opção inscrever-se e caso esteja lotada aparece a opção evento lotado
                                    if (in_array($linha->id, $eventos_inscritos)) {
                                        // montamos o modal
                                        makeModal($linha->id, button_text: 'Cancelar inscrição',
                                            modal_title: 'Cancelar inscrição',
                                            modal_body: 'Tem certeza que deseja cancelar a inscrição?',
                                            confirm_text: 'Sim, cancelar',
                                            form_action: 'index.php?voluntary=1');
                                    } else if ($linha->inscritos >= $linha->lotacao_max) {
                                        // botão padrão <A>
                                        makeButton("Evento Lotado", "btn btn-secondary");
                                    } else {
                                        // botão que envia informações
                                        makeFormButton('index.php?voluntary=3', 'id_evento', $linha->id, 'Inscrever-se');
                                    }
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
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
