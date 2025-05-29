<?php
include_once (ROOT . "/php/config/database_php.php");
include_once (ROOT . '/php/handlers/form_validator_php.php');
include_once (ROOT . "/components/sidebars/sidebars.php");
include_once (ROOT . "/models/voluntary_models_php.php");
include_once (ROOT . "/models/donator_models_php.php");
include_once (ROOT . "/components/table/tables.php");
include_once (ROOT . "/components/back/back.php");

$conn = connectDatabase();
$selected_item = empty($_POST["id_search"] ?? ($_POST["item"] ?? null)) ? null : $_POST["id_search"] ?? ($_POST["item"] ?? null);
$settlements_query_result = get_all_settlements($conn, $selected_item);
$table_head = ["Assentamento", "Estoque", "Famílias dependentes", "Fatia", "Qtd. Atual", "Entrada", "Total"];
$fetched_data = [];
$available_quantity = 0;
while ($data = $settlements_query_result->fetch_object()) {
    $fetched_data[] = clone $data;
}
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $available_quantity = get_available_quantity($fetched_data);
    if (isset($_POST['final']) && $_POST['final']) {
        validate_distribution($conn, $available_quantity,  $fetched_data[0]->id_item);
        $fetched_data = [];
        $settlements_query_result = get_all_settlements($conn, $selected_item);
        while ($data = $settlements_query_result->fetch_object()) {
            $fetched_data[] = clone $data;
        }
        $available_quantity = get_available_quantity($fetched_data);
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/form-style.css">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/main-content.css">
    <title>Acalento | Atualizar Evento</title>
</head>

<body>
<?php make_mobile_sidebar() ?>
<div class="d-flex flex-nowrap">
    <!--    monta a sidebar desktop-->
    <?php make_sidebar(); ?>
    <div class="main-content">
        <main class="px-5 row justify-content-center">
            <div class="container-fluid">
                <div class="mb-3">
                    <?php make_buttom_back("index.php?common=6"); ?>
                    <h4 class="mt-5">Distribuir doacões</h4>
                    <form method="post" action="" class="w-50 mb-5">
                        <div class=" d-inline-flex align-items-center gap-5">
                            <select name="id_search" id="inputItem" class="form-select">
                                <option value="0">Selecione o item</option>
                                <?php $item = $conn->query("SELECT id, nome FROM opcao_item_doacao");
                                while ($a = $item->fetch_object()) { ?>
                                    <option value="<?php echo $a->id;?>" <?= ($selected_item == $a->id) ? 'selected' : '' ?>><?php echo $a->nome ?></option>
                                <?php } ?>
                            </select>
                            <?php makeButton("Escolher", "btn btn-primary w-100", "", true );?>
                        </div>
                    </form>

                    <form id="distribution" class="row g-3" method="POST" action="">
                        <input type="hidden" id="final" name="final" value="<?=1?>">
                        <input type="hidden" id="item" name="item" value="<?=$fetched_data[0]->id_item ?? null?>">
                        <?php
                        if (!$settlements_query_result) {
                            showError(7);
                        }
                        else if (empty($selected_item)) {
                            echo '<h3 class="pb-2">Selecione um item</h3>';
                        }
                        else if (count($fetched_data) <= 0) {
                            echo '<h3 class="pb-2">Não há em estoque</h3>';
                        }
                        else {?>
                        <?php
                            render_distribution_table($table_head, $fetched_data);
                        }
                        ?>
                    </form>
                    <!-- aqui termina -->
                </div>
            </div>
        </main>
    </div>
</div>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const inputs = document.querySelectorAll("input[id^='inputQuantidade']");
        inputs.forEach(input => {
            input.addEventListener("input", function () {
                const valor = this.value;
                const apenasNumeros = valor.replace(/\D/g, '');
                if (apenasNumeros.length > 3) {
                    this.value = apenasNumeros.slice(0, 3);
                } else {
                    this.value = apenasNumeros;
                }
                const idEstoque = this.id.replace("inputQuantidade", "");
                const atualElement = document.getElementById(`actual${idEstoque}`);
                const totalElement = document.getElementById(`total${idEstoque}`);
                const valorAtual = parseFloat(atualElement.textContent) || 0;
                const valorInput = parseFloat(this.value) || 0;
                totalElement.textContent = (valorAtual + valorInput);
            });
        });
    });
</script>
</html>
<?php
function distribute($conn, $item_id)
{
    $itens = [];
    $quantity = [];
    $distribution = [];
    foreach ($_POST as $key => $value) {
        if ($key == "item" || $key == "final") {
            continue;
        }
        $itens[] = $key;
        $quantity[] = intval($value);

    }
    $inventory_ids = array_map(function($item) {
        return (int) filter_var(trim($item), FILTER_SANITIZE_NUMBER_INT);
    }, $itens);
    for ($i = 0; $i < count($inventory_ids); $i++) {
        $distribution[$inventory_ids[$i]] = $quantity[$i];
    }
    foreach ($distribution as $inventory_id => $quantity) {
        distribute_donations($conn, $inventory_id, $quantity, $item_id);
    }
}

function validate_distribution($conn, $available_quantity, $item_id) {
    $sum_itens = 0;

    foreach ($_POST as $key => $value) {
        if ($key == "item" || $key == "final") {
            continue;
        }

        $sum_itens += intval($value);
    }
    if ($sum_itens > $available_quantity) {
        showError(610);
    }
    else {
        distribute($conn, $item_id);
        showSucess(610);
    }
}

function get_available_quantity($fetched_data)
{
    foreach ($fetched_data as $data) {
        if ($data->id_estoque == 1 && isset($data->total_item)) {
            return $data->total_item;
        }
    }
}