<?php
if (isset($_POST['descricao'])) {
    echo $_POST['data'];
    $obj = conecta_db();
    $query = "
    INSERT INTO evento(nome, descricao, data, lotacao, imagem) 
    VALUES ('". $_POST['nome'] ."',
    '". $_POST['descricao'] ."',
        '". $_POST['data'] ."',
    ". $_POST['lotacao'] .",
    '". $_POST['imagem'] ."'
    )";
    echo $query;
    $resultado = $obj->query($query);
    if($resultado) {
        Header("Location: index.php");
    } else {
        echo "<span class='alert alert-danger'>Não Funcionou!</span>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>CRUD EXP CRIATIVA</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h2>CRUD INSERT</h2>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <form method="POST" action="index.php?page=1">
                    <input type="text" name="nome" class="form-control" placeholder="Digite o nome aqui!">
                    <input type="text" name="descricao" class="form-control" placeholder="Digite sua descrição aqui!">
                    <input type="datetime-local" name="data" class="form-control">
                    <input type="number" name="lotacao" class="form-control" placeholder="Digite sua lotacao aqui!">
                    <input type="url" name="imagem" class="form-control" placeholder="Digite sua imagem aqui!">
                    <button type="submit" class="mt-2 btn btn-primary">Enviar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
