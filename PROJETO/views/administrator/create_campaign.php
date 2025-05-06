<?php
include (ROOT . "/php/config/database_php.php");
include(ROOT . '/php/handlers/form_validator_php.php');
include(ROOT . "/components/sidebars/sidebars.php");
// conexão com o banco de dado
$conn = connectDatabase();

// query para buscar os eventos no select
$eventos = $conn->query("SELECT id, nome FROM evento WHERE deletado = 0");
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/form-style.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/main-content.css">
    <title>Acalento | Criar campanha de doação</title>
</head>

<body>
<!-- monta a sidebar mobile -->
<?php make_mobile_sidebar() ?>
<div class="d-flex flex-nowrap">
<!--    monta a sidebar desktop-->
    <?php make_sidebar(); ?>
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
                            <!-- $_post[] ?? null -> se o valor da esquerda for verdadeiro ele é usado, caso não é usado o da direita -->
                            <input type="text" class="form-control" id="inputNome" name="nome" value="<?= $_POST['nome'] ?? null ?>">
                            <div id="validacaoNome" class="invalid-feedback">
                                Escolha um nome adequado para a campanha.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="inputData" class="form-label">Data*</label>
                            <input type="date" class="form-control" id="inputData" placeholder="Data" name="data" value="<?= $_POST['data'] ?? null ?>">
                            <div id="validacaoData" class="invalid-feedback">
                                Escolha uma data para a campanha.
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="inputEvento" class="form-label">Destino*</label>
                            <select name="evento_destino" id="inputEvento" class="form-select">
                                <option value="">Selecione o evento</option>
                                <?php while ($a = $eventos->fetch_object()) { ?>
                                    <!-- isset($_POST['evento_destino']) && $_POST['evento_destino'] == $a->id) ? 'selected' : '' -> se a variável já foi definida e ela é igual ao id então o valor da esqueda
                                    é o que vai ser utilizado, caso contrário é o da direita. select é um atributo que pré-seleciona uma opção de um select -->
                                    <option value="<?php echo $a->id;?>" <?= (isset($_POST['evento_destino']) && $_POST['evento_destino'] == $a->id) ? 'selected' : '' ?>><?php echo $a->nome; ?></option>
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

// $_SERVER["REQUEST_METHOD"] é uma variável superglobal de php, ela guarda qual foi o tipo de requisição que foi feito
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    submitInformation($conn);
}

function submitInformation($conn)
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
        // prepara a query e deixa os valores com um placeholder pra no futuro receber algo
        $query = "
        INSERT INTO campanha_doacao(nome, data, evento_destino)
        VALUES ( ?, ?, ?) ";
        $stmt = $conn->prepare($query); // prepara a query para receber valores;
        $stmt->bind_param("ssi", $_POST['nome'], $_POST['data'], $_POST['evento_destino'] ); // coloca os valores nos parametros POR ORDEM
        // primeiro organizar o tipo dos parametros e depois coloca os dados em ordem
        $stmt->execute(); // executa query
        showSucess(1);
        // mysqli_sql_exception: aviso que o php te dá caso algo dê errado com o seu banco de dados
    } catch (mysqli_sql_exception $E) { // se dar um erro fatal
        showError(3);
        exit();
    }
}

