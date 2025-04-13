<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../conexao.php";
$conexao = conecta_db();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $cep = $conexao->real_escape_string($_POST['cep'] ?? '');
        $rua = $conexao->real_escape_string($_POST['rua'] ?? '');
        $numero = $conexao->real_escape_string($_POST['numero'] ?? '');
        $bairro = $conexao->real_escape_string($_POST['bairro'] ?? '');
        $cidade = $conexao->real_escape_string($_POST['cidade'] ?? '');
        $estado = $conexao->real_escape_string($_POST['estado'] ?? '');


        // Transação
        $conexao->begin_transaction();

        try {
            // Cria o novo endereço
            $insert_endereco = $conexao->query("INSERT INTO endereco (cep, rua, numero, bairro, cidade, estado) 
                                              VALUES ('$cep', '$rua', '$numero', '$bairro', '$cidade', '$estado')");

            if (!$insert_endereco) {
                throw new Exception("Erro ao cadastrar endereço: " . $conexao->error);
            }

            $id_endereco = $conexao->insert_id;

            // Diferentes tipos de usuário
            $tipos = $_POST['tipo_usuario'] ?? [];
            $eh_doador = (in_array('pf', $tipos) || in_array('pj', $tipos)) ? 1 : 0;
            $eh_voluntario = in_array('voluntario', $tipos) ? 1 : 0;
            $eh_adm = in_array('admin', $tipos) ? 1 : 0;

            // Pega os dados
            $email = $_POST['email'];
            $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
            $nome = $_POST['nome'];
            $cpf = isset($_POST['cpf']) ? preg_replace('/\D/', '', $_POST['cpf']) : null;
            $cnpj = isset($_POST['cnpj']) ? preg_replace('/\D/', '', $_POST['cnpj']) : null;
            $telefone = isset($_POST['telefone']) ? preg_replace('/\D/', '', $_POST['telefone']) : null;
            $nascimento = $_POST['nascimento'];

            // Insere o usuário
            $query_usuario = "
            INSERT INTO usuario(id_endereco, email, senha, nome, cpf, cnpj, telefone, nascimento, 
                eh_doador, eh_voluntario, eh_adm
            ) VALUES (
                 $id_endereco,
                '$email',
                '$senha',
                '$nome',
                " . ($cpf ? "'$cpf'" : "NULL") . ",
                " . ($cnpj ? "'$cnpj'" : "NULL") . ",
                '$telefone',
                '$nascimento',
                '$eh_doador',
                '$eh_voluntario',
                '$eh_adm'
            )
        ";
            $resultado_usuario = $conexao->query($query_usuario);
            $id_usuario = $conexao->insert_id;


// s[o para ver agr, depois vai encaminhar para a tabela
            $conexao->commit();
            header('Location: ../Visualizar_usuario/Meus_dados.php');
            exit();

        } catch (Exception $e) {
            $conexao->rollback();
            $error = $e->getMessage();
        }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../css/default/default.css">
    <link rel="stylesheet" href="../../css/default/main.css">
    <link rel="stylesheet" href="Cadastro_usuario.css">
    <link rel="stylesheet" href="../../components/forms/form-style.css">
    <title>Cadastro de Usuário</title>

</head>
<body>
<?php include("../../../PROJETO/components/sidebars/sidebar-mobile.php") ?>
<div class="d-flex flex-nowrap">
    <?php include("../../../PROJETO/components/sidebars/sidebars.php") ?>
    <div class="main">
        <main class="px-5 row align-items-center">
    <div class="container mt-5">
        <h2>Cadastro de Usuário</h2>

    <form method="POST" action="">
        <!-- Informações pessoais -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label" for="nome">Nome*</label>
                <input type="text" id="nome" name="nome" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label" for="email">Email*</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
        </div>
        <!-- Tipo de usuário -->
        <div class="row align-items-start">
            <div class="col-md-12 mb-3">
                <label class="form-label">Tipo de usuário*:</label>
                <div class="d-flex flex-wrap">
                    <div class="form-check me-3">
                        <input class="form-check-input" type="checkbox" value="pf" id="pf" name="tipo_usuario[]">
                        <label class="form-check-label" for="pf">Doador PF</label>
                    </div>
                    <div class="form-check me-3">
                        <input class="form-check-input" type="checkbox" value="pj" id="pj" name="tipo_usuario[]">
                        <label class="form-check-label" for="pj">Doador PJ</label>
                    </div>
                    <div class="form-check me-3">
                        <input class="form-check-input" type="checkbox" value="voluntario" id="voluntario" name="tipo_usuario[]">
                        <label class="form-check-label" for="voluntario">Voluntário</label>
                    </div>
                    <div class="form-check me-3">
                        <input class="form-check-input" type="checkbox" value="admin" id="admin" name="tipo_usuario[]">
                        <label class="form-check-label" for="admin">Administrador</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Campos CPF e CNPJ -->
        <div class="row">
            <div class="col-md-6 mb-3 d-none" id="cpf-field">
                <label class="form-label" for="cpf">CPF*</label>
                <input type="text" name="cpf" id="cpf" class="form-control" maxlength="14">
            </div>
            <div class="col-md-6 mb-3 d-none" id="cnpj-field">
                <label class="form-label" for="cnpj">CNPJ*</label>
                <input type="text" name="cnpj" id="cnpj" class="form-control" maxlength="18">
            </div>
        </div>

        <!-- Senha e Confirmar Senha (movido para baixo) -->
        <div class="row mb-4">
            <div class="col-md-6">
                <label for="senha" class="form-label">Senha*</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>
            <div class="col-md-6">
                <label for="confirmar_senha" class="form-label">Confirmar Senha*</label>
                <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label" for="nascimento">Nascimento*</label>
                <input type="date" name="nascimento" id="nascimento" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label" for="telefone">Telefone*</label>
                <input type="text" name="telefone" id="telefone" class="form-control" maxlength="15">
            </div>
        </div>

        <!-- Endereço -->
        <h5 class="mt-4">Endereço</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">CEP*</label>
                <input type="text" name="cep" id="cep" class="form-control" maxlength="9">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Rua*</label>
                <input type="text" name="rua" id="rua" class="form-control">
            </div>
            <div class="col-md-2 mb-3">
                <label class="form-label">Número*</label>
                <input type="text" name="numero" id="numero" class="form-control">
            </div>
            <div class="col-md-5 mb-3">
                <label class="form-label">Bairro*</label>
                <input type="text" name="bairro" id="bairro" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Cidade*</label>
                <input type="text" name="cidade" id="cidade" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Estado*</label>
                <input type="text" name="estado" id="estado" class="form-control">
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </div>
        </div>
    </form>
        </main>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const cepInput = document.getElementById('cep');
        const cpfField = document.getElementById('cpf-field');
        const cnpjField = document.getElementById('cnpj-field');

        // CEP máscara e busca automática
        cepInput.addEventListener('input', e => {
            let v = e.target.value.replace(/\D/g, '');
            if (v.length > 5) v = v.replace(/^(\d{5})(\d)/, '$1-$2');
            e.target.value = v;
        });

        cepInput.addEventListener('blur', e => {
            const cep = e.target.value.replace(/\D/g, '');
            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(res => res.json())
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById('rua').value = data.logradouro || '';
                            document.getElementById('bairro').value = data.bairro || '';
                            document.getElementById('cidade').value = data.localidade || '';
                            document.getElementById('estado').value = data.uf || '';
                        }
                    });
            }
        });

        // Exibição condicional de CPF e CNPJ
        const checkboxes = document.querySelectorAll('input[name="tipo_usuario[]"]');
        checkboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                cpfField.classList.toggle('d-none', !document.getElementById('pf').checked);
                cnpjField.classList.toggle('d-none', !document.getElementById('pj').checked);
            });
        });
    });
</script>
    <script src="confirmacao.js">
</body>
</html>
