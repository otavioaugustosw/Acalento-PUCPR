<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include (ROOT . "/php/config/database_php.php");
include(ROOT . '/php/handlers/formValidator.php');
$obj = connectDatabase();
$eventos = $obj->query("SELECT id, nome FROM evento");
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
    <title>Acalento | Criar campanha de doação</title>
</head>

<body>
<?php include(ROOT . "/components/sidebars/sidebar-mobile.php") ?>
<div class="d-flex flex-nowrap">
    <?php include(ROOT .  "/components/sidebars/sidebars.php") ?>
    <!-- fim sidebar -->

    <!-- conteudo -->
    <div class="main-content ">
        <main class="px-5 row align-items-center justify-content-center">
            <div class="container-fluid">
                <div class="mb-3">
                    <!-- aqui vai o que você quer por -->
                    <h4>Nova Campanha de Doação</h4>
                    <form class="row g-3" method="POST" action="">
                        <!-- para três em uma linha -->
                        <div class="col-md-6">
                            <label for="inputNome" class="form-label">Nome*</label>
                            <input type="text" class="form-control" id="inputNome" name="nome">
                            <div id="validacaoNome" class="invalid-feedback">
                                Escolha um nome adequado para a campanha.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="inputData" class="form-label">Data*</label>
                            <input type="date" class="form-control" id="inputData" placeholder="Data" name="data">
                            <div id="validacaoData" class="invalid-feedback">
                                Escolha uma data para a campanha.
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="inputEvento" class="form-label">Destino*</label>
                            <select name="evento_destino" id="inputEvento" class="form-select">
                                <option value="">Selecione o evento</option>
                                <?php while ($a = $eventos->fetch_object()) { ?>
                                    <option value="<?php echo $a->id;?>"><?php echo $a->nome; ?></option>
                                <?php } ?>
                            </select>
                            <div id="validacaoEvento" class="invalid-feedback">
                                Escolha um evento.
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Salvar evento</button>
                        </div>
                    </form>
                    <!-- aqui termina -->
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
<?php

// PARA INTERAGIR COM OS CAMPOS, O ENVIO DE DADOS DEVE SER APÓS O SITE TER SIDO CARREGADO
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    submitInformation($obj);
}

function submitInformation($sql)
{
    // isAlphaOnly -> SOMENTE LETRAS
    // hasMaxLength -> tamanho máximo da string para não quebrar o SQL
    if (!isAlphaOnly($_POST['nome']) || !hasMaxLength($_POST['nome'], 50)) /*se nome não houver somente letras ou nome não for menor que 50 caracteres*/ {
        displayValidation('inputNome', false); // mostra erro no campo incorreto utilizando ID do input. (FALSE = ERRO, TRUE = SUCESSO)
        return;
    }

    if (!isDateValid($_POST['data'])) {
        displayValidation('inputData', false);
        return;
    }

    if (!isNumericOnly($_POST['evento_destino'])) {
        displayValidation('inputEvento', false);
        return;
    }

    try { // tenta executar a query
        $query = "
        INSERT INTO campanha_doacao(nome, data, evento_destino)
        VALUES ( ?, ?, ?) "; // interrogação equivale aonde os parametros irão serem colocados
        $stmt = $sql->prepare($query); // prepara a query para receber valores;
        $stmt->bind_param("ssi", $_POST['nome'], $_POST['data'], $_POST['evento_destino'] ); // coloca os valores nos parametros POR ORDEM
        // SSI = STRING STRING INT ou seja NOME = STRING, DATA = STRING, EVENTO_DESTINO = INT
        // depois coloca-se os parametro na ordem correta
        $stmt->execute(); // executa query
        showSucess(1);
    } catch (mysqli_sql_exception $E) { // se dar um erro fatal
        showError(3);
        exit();
    }
}

