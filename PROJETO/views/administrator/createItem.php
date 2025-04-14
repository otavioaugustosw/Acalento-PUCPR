<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../teste_conexao/conexao.php";
$obj = conecta_db();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $query = "
        INSERT INTO item(id_campanha_doacao, id_opcao, id_usuario, quantidade, unidade_medida, valor, tipo, data) 
        VALUES (
            '". $_POST['id_campanha_doacao'] ."',
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
        $erro = $obj->error;
        $status = "erro";
    } else {
        $status = "sucesso";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../components/forms/form-style.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/default/default.css">
    <link rel="stylesheet" href="../../css/sidebars.css">
    <link rel="stylesheet" href="../../css/default/main-content.css">
    <title>Title</title>
</head>

<body>
<?php include("../../../PROJETO/components/sidebars/sidebar-mobile.php") ?>
<!-- deixa o body em display-flex-->
<div class="d-flex">
    <?php include("../../../PROJETO/components/sidebars/sidebars.php") ?>
    <!-- fim sidebar -->

    <!-- conteudo -->
    <div class="main-content">
        <main class="px-5 row align-items-center justify-content-center">
            <div class="container-fluid">
                <div class="mb-3">
                    <!-- aqui vai o que você quer por -->
                    <h2>Cadastrar item</h2>
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

                        <div class="col-md-12">
                            <label for="inputEvento" class="form-label">Destino*</label>
                            <select name="id_campanha_doacao" id="inputEvento" class="form-select" required>
                                <option value="">Selecione o evento</option>
                                <?php
                                    $campanha_doacao = $obj->query("SELECT id, nome FROM campanha_doacao");
                                    while ($a = $campanha_doacao->fetch_object()) { ?>
                                    <option value="<?php echo $a->id;?>"><?php echo $a->nome; ?></option>
                                <?php } ?>
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
<?php if (isset($status)): ?>
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div class="toast <?php echo $status === 'sucesso' ? 'text-bg-success' : 'text-bg-danger' ?> show" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <?php
                    if ($status === 'sucesso') {
                        echo "Item salvo com sucesso!";
                    } else {
                        echo "Erro ao salvar item: " . $erro;
                    }
                    ?>
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
<?php endif; ?>
</body>
</html>