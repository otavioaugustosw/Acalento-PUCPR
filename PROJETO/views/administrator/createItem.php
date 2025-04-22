<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include (ROOT . "/php/config/database_php.php");
$obj = connectDatabase();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idCampanha = isset($_POST['id_campanha_doacao']) && $_POST['destino'] === 'campanha' ? $_POST['id_campanha_doacao'] : "NULL";
    $idEstoque = isset($_POST['id_estoque']) && $_POST['destino'] === 'estoque' ? $_POST['id_estoque'] : "NULL";

    $query = "
    INSERT INTO item(id_campanha_doacao, id_estoque, id_opcao, id_usuario, quantidade, unidade_medida, valor, tipo, data) 
    VALUES (
        $idCampanha,
        $idEstoque,
        '". $_POST['id_opcao'] ."',
        '". $_POST['id_usuario'] ."',
        '". $_POST['quantidade'] ."',
        '". $_POST['unidade_medida'] ."',
        '". $_POST['valor'] ."',
        '". $_POST['tipo'] ."',
        '". $_POST['data'] ."'
    )";
    $resultado = $obj->query($query);

    if (!$resultado) {
        showError(5);
    } else {
        showSucess(3);
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
    <link rel="stylesheet" href="css/sidebars.css">
    <link rel="stylesheet" href="css/main-content.css">
    <title>Acalento | Cadastrar item</title>
</head>

<body>
<?php include(ROOT . "/components/sidebars/sidebar-mobile.php") ?>
<div class="d-flex flex-nowrap">
    <?php include(ROOT .  "/components/sidebars/sidebars.php") ?>
    <!-- fim sidebar -->

    <!-- conteudo -->
    <div class="main-content">
        <main class="px-5 row align-items-center justify-content-center">
            <div class="container-fluid">
                <div class="mb-3">
                    <!-- aqui vai o que você quer por -->
                    <h4>Cadastrar item</h4>
                    <form class="row g-3" method="POST" action="">
                        <div class="col-md-4">
                            <label for="inputDoador" class="form-label">Doador*</label>
                            <select name="id_usuario" id="inputDoador" class="form-select" required>
                                <option value="">Selecione o doador</option>
                                <?php $doador = $obj->query("SELECT id, nome FROM usuario");
                                while ($a = $doador->fetch_object()) { ?>
                                    <option value="<?php echo $a->id;?>"><?php echo $a->nome; ?></option>
                                <?php } ?>
                                <option value="0">Doador não cadastrado</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="inputData" class="form-label">Data*</label>
                            <input type="date" class="form-control" id="inputData" placeholder="Data" name="data" required>
                        </div>

                        <div class="col-md-4">
                            <label for="inputItem" class="form-label">Item*</label>
                            <select name="id_opcao" id="inputItem" class="form-select" required>
                                <option value="">Selecione o item</option>
                                <?php $item = $obj->query("SELECT id, nome FROM opcao_item");
                                while ($a = $item->fetch_object()) { ?>
                                    <option value="<?php echo $a->id;?>"><?php echo $a->nome; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="inputQuantidade" class="form-label">Quantidade</label>
                            <input type="number" class="form-control" id="inputQuantidade" name="quantidade">
                        </div>
                        <div class="col-md-3">
                            <label for="inputUnidadeMedida" class="form-label">Unidade de Medida</label>
                            <input type="text" class="form-control" id="inputUnidadeMedida" name="unidade_medida">
                        </div>
                        <div class="col-md-3">
                            <label for="inputValor" class="form-label">Valor</label>
                            <input type="number" class="form-control" id="inputValor" step="0.01" name="valor" placeholder="R$00.00">
                        </div>
                        <div class="col-md-3">
                            <label for="inputTipo" class="form-label">Tipo*</label>
                            <select id="inputItem" name="tipo" required class="form-select">
                                <option value="">Selecione o tipo</option>
                                <?php
                                // Valores fixos do ENUM (os mesmos definidos na tabela)
                                $valores_enum = ['Alimentício', 'Brinquedo', 'Limpeza', 'Outros'];

                                foreach ($valores_enum as $valor) { ?>
                                    <option value="<?php echo $valor; ?>">
                                        <?php echo $valor; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Destino do item*</label>
                            <select id="selectDestino" name="destino" class="form-select" required onchange="mostrarDestino()">
                                <option value="">Selecione</option>
                                <option value="campanha">Campanha</option>
                                <option value="estoque">Estoque</option>
                            </select>
                        </div>

                        <div class="col-md-10" id="campoCampanha" style="display: none;">
                            <label class="form-label">Campanha</label>
                            <select name="id_campanha_doacao" class="form-select">
                                <option value="">Selecione a campanha</option>
                                <?php
                                $campanhas = $obj->query("SELECT id, nome FROM campanha_doacao");
                                while ($c = $campanhas->fetch_object()) { ?>
                                    <option value="<?php echo $c->id; ?>"><?php echo $c->nome; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-10" id="campoEstoque" style="display: none;">
                            <label class="form-label">Estoque</label>
                            <select name="id_estoque" class="form-select">
                                <option value="1">Estoque geral</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Salvar item</button>
                        </div>
                    </form>
                    <!-- aqui termina -->
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    function mostrarDestino() {
        const tipo = document.getElementById('selectDestino').value;
        document.getElementById('campoCampanha').style.display = tipo === 'campanha' ? 'block' : 'none';
        document.getElementById('campoEstoque').style.display = tipo === 'estoque' ? 'block' : 'none';
    }
</script>

</body>
</html>