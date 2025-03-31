<?php
if (isset($_POST['descricao'])) {
    $obj = conecta_db();
    $query = "
    UPDATE evento 
    SET
    nome = '". $_POST['nome'] ."',
    descricao = '". $_POST['descricao'] ."',
    data = '". $_POST['data'] ."',
    lotacao = ". $_POST['lotacao'] .",
    imagem = '". $_POST['imagem'] ."'
    WHERE id = ". $_GET['id'] ."
    ";
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
            <h2>ALTERAR</h2>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <?php
            $obj = conecta_db();
            $query = "SELECT * FROM evento WHERE id = '". $_GET['id'] ."'";
            $resultado = $obj->query($query);
            if($linha = $resultado->fetch_object()) { ?>
                <form method="POST" action="index.php?id=<?=$_GET['id']?>&page=3">
                    <input type="text" name="nome" class="form-control" placeholder="Digite o nome aqui!" value="<?php echo $linha->nome?>">
                    <input type="text" name="descricao" class="form-control" placeholder="Digite sua descrição aqui!" value="<?php echo $linha->descricao?> ">
                    <input type="datetime-local" name="data" class="form-control" value="<?php echo $linha->data?>">
                    <input type="number" name="lotacao" class="form-control" placeholder="Digite sua lotacao aqui!" value="<?php echo $linha->lotacao?>">
                    <input type="url" name="imagem" class="form-control" placeholder="Digite sua imagem aqui!" value="<?php echo $linha->imagem?>">
                    <button type="submit" class="mt-2 btn btn-primary">Enviar</button>
                </form>
            <?php
            } else {

            }
            ?>

        </div>
    </div>
</div>
</body>
</html>

