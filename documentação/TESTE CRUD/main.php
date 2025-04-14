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
                <h2>Acalento</h2>
            </div>
        </div>
        <div class="row">
            <div class="col">
            <a href="index.php?page=1" class="btn btn-primary"> Adicionar novo registro</a>
            </div>
        </div>
    </div>
           <div class="card-deck" style="padding: 2vw">
               <div class="row">

            <?php
                        $obj = conecta_db();
                        $query = "SELECT senha FROM usuario WHERE email = ?";
                        $resultado = $obj->query($query);
                        while ($linha = $resultado->fetch_object()) {
                            ?>
                                    <div class="card" style="width: 18rem;">
                                        <img class="card-img-top"  style="height: 15rem; object-fit: cover;" src="<?php echo $linha->imagem?>" alt="Card image cap">
                                        <div class="card-body">
                                            <h4><?php echo $linha->nome?></h4>
                                            <p class="card-text"> <?php echo $linha->data?></p>
                                            <p class="card-text"> 0/<?php echo $linha->lotacao?> inscritos</p>
                                            <p class="card-text"> <?php echo $linha->descricao?></p>
                                            <a href="index.php?page=2&id=<?php echo $linha->id?>"  class="btn btn-danger">Deletar</a>
                                            <a href="index.php?page=3&id=<?php echo $linha->id?>" class="btn btn-primary">Alterar</a>
                                        </div>
                                    </div>
                                 <?php
                        }
                        ?>
               </div>
            </div>
        </div>
</body>
</html>
