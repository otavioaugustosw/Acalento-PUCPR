<?php
include (ROOT . "/php/config/database_php.php");
include (ROOT . "/php/handlers/formValidator.php");
$obj = connectDatabase();
$id_usuario = $_SESSION['USER_ID'];
$id_endereco = $_SESSION['USER_ADDRESS_ID'];

//pega os dados do usuário e endereço
$query = "SELECT u.*, e.* 
          FROM usuario u 
          LEFT JOIN endereco e ON u.id_endereco = e.id 
          WHERE u.id =" . $_SESSION['USER_ID'];
$resultado = $obj->query($query);

if (!$resultado) {
    showError(1);
}

$dados = $resultado->fetch_object();

if (!$dados) {
    showError(1);
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
    <title>Meus Dados</title>
</head>
<body>
<?php include(ROOT . "/components/sidebars/sidebar-mobile.php") ?>
<div class="d-flex flex-nowrap">
    <?php include(ROOT .  "/components/sidebars/sidebars.php") ?>


    <div class="flex-grow-1 p-4 main-content">
        <main class="container-fluid align-content-center">
            <h2 class="my-4">Editar Meus Dados</h2>


            <form action="index.php?common=8" method="post">
            <!-- Dados Pessoais -->
                <div class="mb-4">
                    <h5 class="mb-3">Informações Pessoais</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" name="nome"
                                   value="<?= $dados->nome ?? '' ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email"
                                   value="<?= $dados->email ?? '' ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">CPF</label>
                            <input type="text" class="form-control" name="cpf"
                                   value="<?= $dados->cpf ?? '' ?>">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Telefone</label>
                            <input type="text" class="form-control" name="telefone"
                                   value="<?= $dados->telefone ?? '' ?>">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Data de Nascimento</label>
                            <input type="date" class="form-control" name="nascimento"
                                   value="<?= $dados->nascimento ?? '' ?>">
                        </div>
                    </div>
                </div>

            <!-- Endereço -->
                <div class="mb-4">
                <h5 class="mb-3">Endereço</h5>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">CEP</label>
                            <input type="text" class="form-control" name="cep" id="cep"
                                   value="<?= $dados->cep ?? '' ?>" maxlength="9">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Logradouro</label>
                            <input type="text" class="form-control" name="rua" id="rua"
                                   value="<?= $dados->rua ?? '' ?>" maxlength="50">
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">Número</label>
                            <input type="text" class="form-control" name="numero" id="numero"
                                   value="<?= $dados->numero ?? '' ?>" maxlength="6">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Complemento </label>
                            <input type="text" class="form-control" id="complemento" name="complemento"
                                   maxlength="50" value="<?= $dados->complemento ?? '' ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Bairro</label>
                            <input type="text" class="form-control" name="bairro" id="bairro"
                                   value="<?= $dados->bairro ?? '' ?>" maxlength="50">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Cidade</label>
                            <input type="text" class="form-control" name="cidade" id="cidade"
                                   value="<?= $dados->cidade ?? '' ?>" maxlength="50">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Estado</label>
                            <input type="text" class="form-control" name="estado" id="estado"
                                   value="<?= $dados->estado ?? '' ?>" maxlength="50">
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="invisible">Confirmar</div>
                            <button type="submit" class="btn btn-primary mt-1">Salvar Alterações</button>
                        </div>
                    </div>

                </div>
                </div>
            </form>
        </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/confirmation.js"></script>
</body>
</html>

<?php
function validateAddress($sql) {
    if (!isNumericOnly(preg_replace('/\D/', '', $_POST['cep'])) || !hasMaxLength(preg_replace('/\D/', '', $_POST['cep']), 8)) {
        displayValidation('cep' , false);
        return false;
    }

    if (!isAlphaOnly($_POST['rua']) || !hasMaxLength($_POST['rua'], 50)) {
        displayValidation('rua', false);
        return false;
    }

    if (!isNumericOnly($_POST['numero']) || !hasMaxLength($_POST['numero'], 50)) {
        displayValidation('numero', false);
        return false;
    }

    if (!isAlphaOnly($_POST['bairro']) || !hasMaxLength($_POST['bairro'], 50)) {
        displayValidation('bairro', false);
        return false;
    }

    if (!isAlphaOnly($_POST['cidade']) || !hasMaxLength($_POST['cidade'], 50)) {
        displayValidation('cidade', false);
        return false;
    }

    if (!isAlphaOnly($_POST['estado']) || !hasMaxLength($_POST['estado'], 50)) {
        displayValidation('estado', false);
        return false;
    }
    return true;
}

function validateUser($sql)
{
    if (!isFullName($_POST['nome']) || !hasMaxLength($_POST['nome'], 50)) {
        displayValidation('nome', false);
        return false;
    }

//    if (!isCPFValid($_POST['cpf'])) {
//        displayValidation('cpf' , false);
//        return false;
//    }

    if (!isNumericOnly(preg_replace('/\D/', '', $_POST['telefone']))) {
        displayValidation('telefone', false);
        return false;
    }

    if (!isValidEmail($_POST['email'])) {
        displayValidation('email', false);
        return false;
    }


    if (!isDateValid($_POST['nascimento'])) {
        displayValidation('nascimento', false);
        return false;
    }
    return true;
}

function verifyUserExistence($sql, $email, $cpf)
{
    try {
        // Verifica se já existe cadastro com o Email
        $query = "SELECT email, id FROM usuario WHERE email = ? AND id != ?";
        $stmt = $sql->prepare($query);
        $stmt->bind_param("si", $email, $_SESSION['USER_ID']);
        $stmt->execute();
        $resultEmail = $stmt->get_result();

        if ($resultEmail->num_rows > 0) {
            showError(16);
            var_dump($resultEmail);
            return true;
        }

        // Verifica se já existe cadastro com o CPF
        $query = "SELECT cpf, id FROM usuario WHERE cpf = ? AND id != ?";
        $stmt = $sql->prepare($query);
        $stmt->bind_param("si", $cpf, $_SESSION['USER_ID']);
        $stmt->execute();
        $resultCpf = $stmt->get_result();

        if ($resultCpf->num_rows > 0) {
            showError(19);
            var_dump($resultCpf);
            return true;
        }
        // Não encontrou nem email nem cpf já cadastrados
        return false;

    } catch (Exception $e) {
        showError(15);
    }
}

function submitUser($sql, $id_usuario, $id_endereco)
{
    $cep = preg_replace('/\D/', '', $_POST['cep']);
    $rua = $_POST['rua'];
    $numero = $_POST['numero'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $complemento = $_POST['complemento'];

    try {
        // cria o novo endereço
        $query = "UPDATE endereco
        SET cep = ?, rua = ?, numero = ?, bairro = ?, cidade = ?, estado = ?, complemento = ? WHERE id = ?";
        $stmt = $sql->prepare($query);
        $stmt->bind_param("ssissssi", $cep, $rua, $numero, $bairro, $cidade, $estado, $complemento, $id_endereco);
        $stmt->execute();
    } catch (Exception $e) {
        showError(15);
    }

    $id_endereco = $_SESSION['USER_ADDRESS_ID'];
    $email = $_POST['email'];
    $nome = $_POST['nome'];
    $cpf = preg_replace('/\D/', '', $_POST['cpf']);
    $telefone = preg_replace('/\D/', '', $_POST['telefone']);
    $nascimento = $_POST['nascimento'];

    try {
        $query = "UPDATE usuario 
        SET id_endereco = ?, email = ?, nome = ?, cpf = ?, telefone = ?, nascimento = ? WHERE id = ?";
        $stmt = $sql->prepare($query);
        $stmt->bind_param("isssssi", $id_endereco, $email, $nome, $cpf, $telefone, $nascimento, $id_usuario);
        $stmt->execute();
    } catch (Exception $e) {
        showError(15);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (validateUser($obj) && validateAddress($obj)) {
        $cpf = preg_replace('/\D/', '', $_POST['cpf']);
        $email = $_POST['email'];
        if (verifyUserExistence($obj, $email, $cpf)) {
        } else {
            submitUser($obj, $id_usuario, $id_endereco);
            ?>
            <script>
                window.location.href = "index.php?common=7";
            </script>
            <?php
        }
    }
}
?>