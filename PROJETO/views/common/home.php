<?php
include ('php/config/session.php');
include(ROOT . '/php/config/database_php.php');
$sql = connectDatabase();
function checkMark($value): string
{
    return $value ? '✅ Sim' : '❌ Não';
}
include 'header.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link href="css/form-style.css" rel="stylesheet">
    <link href="css/default.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">
</head>
<body class="justify-content-center align-items-center vh-100">
<?php
if(isset($_SESSION['USER_ID'])) {
    $query = "SELECT * FROM endereco WHERE id = ?";
    $stmt = $sql->prepare($query);
    $stmt->bind_param("i", $_SESSION['USER_ADDRESS_ID']);
    $stmt->execute();
    $result = $stmt->get_result();
    $address = $result->fetch_object();
?>
    <div class="card shadow-lg p-5 w-50">
        <h2 class="mb-4 text-center">Olá, <?=($_SESSION['USER_NAME'])?>!</h2>
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Email:</strong> <?=$_SESSION['USER_EMAIL']?></li>
            <li class="list-group-item"><strong>Administrador:</strong> <?=checkMark($_SESSION['USER_IS_ADMINISTRATOR'])?></li>
            <li class="list-group-item"><strong>Voluntário:</strong> <?=checkMark($_SESSION['USER_IS_VOLUNTARY'])?></li>
            <li class="list-group-item"><strong>Doador:</strong> <?=checkMark($_SESSION['USER_IS_DONATOR'])?></li>
            <li class="list-group-item"><strong>Rua:</strong> <?=$address->rua?></li>
            <li class="list-group-item"><strong>Cidade:</strong> <?=$address->cidade?></li>
            <li class="list-group-item"><strong>Estado:</strong> <?=$address->estado?></li>
        </ul>
        <a class="btn btn-primary" href="index.php?adm=1">IRA PARA CRIAÇÃO DE CAMPANHA TESTE</a>
    </div>
<?php }?>
</body>
</html>

