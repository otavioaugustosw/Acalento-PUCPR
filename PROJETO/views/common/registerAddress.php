<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include (ROOT . "/php/config/database_php.php");
$conexao = connectDatabase();

// pega ID do usuário criado na página anterior
$id_usuario = isset($_GET['id_usuario']) ? intval($_GET['id_usuario']) : null;

// Verifica se o formulário foi submetido (POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtém os dados do endereco
    $id_usuario = isset($_POST['id_usuario']) ? intval($_POST['id_usuario']) : null;
    $cep = $obj->real_escape_string($_POST['cep'] ?? '');
    $rua = $obj->real_escape_string($_POST['rua'] ?? '');
    $numero = $obj->real_escape_string($_POST['numero'] ?? '');
    $bairro = $obj->real_escape_string($_POST['bairro'] ?? '');
    $cidade = $obj->real_escape_string($_POST['cidade'] ?? '');
    $estado = $obj->real_escape_string($_POST['estado'] ?? '');

    // Verifica se o usuário existe no banco de dados
    $check_user = $obj->query("SELECT id FROM usuario WHERE id = $id_usuario");
    if ($check_user->num_rows == 0) {
        die("<span class='alert alert-danger'>Erro: Usuário não encontrado!</span>");
    }

    // transação para evitar que desencontre as atualizacoes do bd
    $obj->begin_transaction();

    try {
        // cria o novo endereço
        $insert_endereco = $obj->query("INSERT INTO endereco (cep, rua, numero, bairro, cidade, estado) 
                                      VALUES ('$cep', '$rua', '$numero', '$bairro', '$cidade', '$estado')");

        if (!$insert_endereco) {
            throw new Exception("Erro ao cadastrar endereço: " . $obj->error);
        }

        // pega o id do endereco para passar no usuario
        $id_endereco = $obj->insert_id;

        // coloca o id_endereco no usuario
        $update_usuario = $obj->query("UPDATE usuario SET id_endereco = $id_endereco WHERE id = $id_usuario");


        // Confirma se deu certo
        $obj->commit();
        echo "<span class='alert alert-success'>Endereço cadastrado e vinculado ao usuário com sucesso!</span>";

    } catch (Exception $e) {
        // se deu erro, desfaz tudo
        $obj->rollback();
        echo "<span class='alert alert-danger'>" . $e->getMessage() . "</span>";
    }
    header('Location: ../Visualizar_usuario/view_user.php');
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/form-style.css">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/sidebars.css">
    <link rel="stylesheet" href="css/main-content.css">
    <link rel="stylesheet" href="css/header.css">
    <title>Cadastro de endereço</title>
</head>

<body>

<header>
    <div class="container">
        <div class="logo">
            <img src="assets/logo/acalento-logo.svg" alt="logo acalento">
            <h1>Projeto Acalento</h1>
        </div>
        <nav>
            <ul>
                <li><a href="#">nossa missão</a></li>
                <li><a href="#">blog acalento</a></li>
                <li><a href="#">nos apoie!</a></li>
                <li><a href="#">portal transparência</a></li>
            </ul>
        </nav>
    </div>
</header>

<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg p-4">
                <h2 class="form-title">Informações residenciais</h2>

    <!-- id do usuario escondido -->
    <form method="POST" action="">
        <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($id_usuario) ?>">

        <!-- CEP -->
        <div class="mb-3 mt-2">
            <label for="cep" class="form-label">CEP *</label>
            <input type="text" class="form-control border-dark-subtle" id="cep" name="cep" required maxlength="9" pattern="\d{5}-\d{3}">
        </div>

        <!-- rua -->
        <div class="mb-3">
            <label for="rua" class="form-label">Logradouro *</label>
            <input type="text" class="form-control border-dark-subtle" id="rua" name="rua" required maxlength="50">
        </div>

        <!-- bairro -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="bairro" class="form-label">Bairro *</label>
                <input type="text" class="form-control border-dark-subtle" id="bairro" name="bairro" required maxlength="50">
            </div>

            <!-- Número -->
            <div class="col-md-6 mb-3">
                <label for="numero" class="form-label">Número *</label>
                <input type="number" class="form-control border-dark-subtle" id="numero" name="numero" required maxlength="6">
            </div>
        </div>

        <!-- Cidade -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="cidade" class="form-label">Cidade *</label>
                <input type="text" class="form-control border-dark-subtle" id="cidade" name="cidade" required maxlength="50">
            </div>

            <!-- Estado-->
            <div class="col-md-6 mb-3">
                <label for="estado" class="form-label">Estado *</label>
                <input type="text" class="form-control border-dark-subtle" id="estado" name="estado" required maxlength="50">
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-primary">Finalizar Cadastro</button>
        </div>

    </form>
</div>

</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Máscara para CEP
        const cepInput = document.getElementById('cep');

        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/^(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        });

        // Busca automática do endereço quando o CEP estiver completo
        cepInput.addEventListener('blur', function(e) {
            const cep = e.target.value.replace(/\D/g, '');

            if (cep.length === 8) {
                buscarEndereco(cep);
            }
        });
    });

    function buscarEndereco(cep) {
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                if (!data.erro) {
                    // Preenche os campos automaticamente
                    document.getElementById('rua').value = data.logradouro || '';
                    document.getElementById('bairro').value = data.bairro || '';
                    document.getElementById('cidade').value = data.localidade || '';
                    document.getElementById('estado').value = data.uf || '';

                    // Foca no campo número após preencher os dados
                    document.getElementById('numero').focus();
                } else {
                    alert('CEP não encontrado!');
                }
            });
    }
</script>
</html>