<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include (ROOT . "/php/config/database_php.php");
$obj = connectDatabase();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $query = "
        INSERT INTO campanha_doacao(nome, data, evento_destino) 
        VALUES (
            '". $_POST['nome'] ."',
            '". $_POST['data'] ."',
            '". $_POST['evento_destino'] ."'
        )";
    $resultado = $obj->query($query);

    if (!$resultado) {
        $erro = $obj->error;
        $status = "erro";
    } else {
        $status = "sucesso";
    }
}

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
    <div class="main-content">
        <main class="px-5 row align-items-center justify-content-center">
            <div class="container-fluid">
                <div class="mb-3">
                    <!-- aqui vai o que você quer por -->
                    <h2>Nova Campanha de Doação</h2>
                    <form class="row g-3" method="POST" action="">
                        <!-- para três em uma linha -->
                        <div class="col-md-6">
                            <label for="inputNome" class="form-label">Nome*</label>
                            <input type="text" class="form-control" id="inputNome" name="nome" required>
                        </div>
                        <div class="col-md-6">
                            <label for="inputData" class="form-label">Data*</label>
                            <input type="date" class="form-control" id="inputData" placeholder="Data" name="data" required>
                        </div>
                        <div class="col-md-12">
                            <label for="inputEvento" class="form-label">Destino*</label>
                            <select name="evento_destino" id="inputEvento" class="form-select" required>
                                <option value="">Selecione o evento</option>
                                <?php while ($a = $eventos->fetch_object()) { ?>
                                    <option value="<?php echo $a->id;?>"><?php echo $a->nome; ?></option>
                                <?php } ?>
                            </select>
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
<?php if (isset($status)): ?>
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div class="toast <?php echo $status === 'sucesso' ? 'text-bg-success' : 'text-bg-danger' ?> show" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <?php
                    if ($status === 'sucesso') {
                        echo "Evento salvo com sucesso!";
                    } else {
                        echo "Erro ao salvar evento: " . $erro;
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


