<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include (ROOT . "/php/config/database_php.php");
$conexao = connectDatabase();

$id_usuario = $_SESSION['USER_ID'];

//pega os dados do usuário e endereço
$query = "SELECT u.*, e.* 
          FROM usuario u 
          LEFT JOIN endereco e ON u.id_endereco = e.id 
          WHERE u.id = $id_usuario";
$resultado = $conexao->query($query);

if (!$resultado) {
    die("<div class='alert alert-danger'>Erro na consulta: " . $conexao->error . "</div>");
}

$dados = $resultado->fetch_object();

if (!$dados) {
    die("<div class='alert alert-warning'>Usuário não encontrado</div>");
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Atualiza os dados do usuário
    $query_usuario = "UPDATE usuario SET
                     nome = '" . $conexao->real_escape_string($_POST['nome'] ?? '') . "',
                     email = '" . $conexao->real_escape_string($_POST['email'] ?? '') . "',
                     cpf = '" . $conexao->real_escape_string($_POST['cpf'] ?? '') . "',
                     telefone = '" . $conexao->real_escape_string($_POST['telefone'] ?? '') . "',
                     nascimento = '" . $conexao->real_escape_string($_POST['nascimento'] ?? '') . "'
                     WHERE id = $id_usuario";

    $resultado_usuario = $conexao->query($query_usuario);

    // Se não tem endereço: INSERE novo e atualiza id_endereco
    if (empty($dados->id_endereco)) {
        $query_endereco_insert = "INSERT INTO endereco (cep, rua, numero, bairro, cidade, estado)
                                  VALUES (
                                      '" . $conexao->real_escape_string($_POST['cep'] ?? '') . "',
                                      '" . $conexao->real_escape_string($_POST['rua'] ?? '') . "',
                                      '" . $conexao->real_escape_string($_POST['numero'] ?? '') . "',
                                      '" . $conexao->real_escape_string($_POST['bairro'] ?? '') . "',
                                      '" . $conexao->real_escape_string($_POST['cidade'] ?? '') . "',
                                      '" . $conexao->real_escape_string($_POST['estado'] ?? '') . "'
                                  )";

        $resultado_endereco = $conexao->query($query_endereco_insert);

        if ($resultado_endereco) {
            $novo_id_endereco = $conexao->insert_id;
            $conexao->query("UPDATE usuario SET id_endereco = $novo_id_endereco WHERE id = $id_usuario");
        }

    } else {
        // Já tem endereço → ATUALIZA
        $query_endereco_update = "UPDATE endereco SET
                                  cep = '" . $conexao->real_escape_string($_POST['cep'] ?? '') . "',
                                  rua = '" . $conexao->real_escape_string($_POST['rua'] ?? '') . "',
                                  numero = '" . $conexao->real_escape_string($_POST['numero'] ?? '') . "',
                                  bairro = '" . $conexao->real_escape_string($_POST['bairro'] ?? '') . "',
                                  cidade = '" . $conexao->real_escape_string($_POST['cidade'] ?? '') . "',
                                  estado = '" . $conexao->real_escape_string($_POST['estado'] ?? '') . "'
                                  WHERE id = " . $dados->id_endereco;

        $resultado_endereco = $conexao->query($query_endereco_update);
    }

    // Verifica se tudo deu certo
    if (!$resultado_usuario || !$resultado_endereco) {
        echo "<div class='alert alert-danger'>Erro ao atualizar: " . $conexao->error . "</div>";
        exit();
    } else {
        echo "<div class='alert alert-success'>Dados atualizados com sucesso!</div>";
        // Recarrega os dados atualizados
        $resultado = $conexao->query($query);
        $dados = $resultado->fetch_object();
    }
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



            <!-- Dados Pessoais -->
                <div class="mb-4">
                    <h5 class="mb-3">Informações Pessoais</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nome Completo</label>
                        <input type="text" class="form-control border-dark-subtle" name="nome"
                               value="<?= htmlspecialchars($dados->nome ?? '') ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control border-dark-subtle" name="email"
                               value="<?= htmlspecialchars($dados->email ?? '') ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">CPF</label>
                        <input type="text" class="form-control border-dark-subtle" name="cpf"
                               value="<?= htmlspecialchars($dados->cpf ?? '') ?>">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Telefone</label>
                        <input type="text" class="form-control border-dark-subtle" name="telefone"
                               value="<?= htmlspecialchars($dados->telefone ?? '') ?>">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Data de Nascimento</label>
                        <input type="date" class="form-control border-dark-subtle" name="nascimento"
                               value="<?= htmlspecialchars($dados->nascimento ?? '') ?>">
                    </div>
                </div>
            </div>

            <!-- Endereço -->
            <div class="mb-4">
                <h5 class="mb-3">Endereço</h5>

                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label class="form-label">CEP</label>
                        <input type="text" class="form-control border-dark-subtle" name="cep" id="cep"
                               value="<?= htmlspecialchars($dados->cep ?? '') ?>" maxlength="9">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Logradouro</label>
                        <input type="text" class="form-control border-dark-subtle" name="rua" id="rua"
                               value="<?= htmlspecialchars($dados->rua ?? '') ?>" maxlength="50">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label">Número</label>
                        <input type="text" class="form-control border-dark-subtle" name="numero" id="numero"
                               value="<?= htmlspecialchars($dados->numero ?? '') ?>" maxlength="6">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label">Bairro</label>
                        <input type="text" class="form-control border-dark-subtle" name="bairro" id="bairro"
                               value="<?= htmlspecialchars($dados->bairro ?? '') ?>" maxlength="50">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Cidade</label>
                        <input type="text" class="form-control border-dark-subtle" name="cidade" id="cidade"
                               value="<?= htmlspecialchars($dados->cidade ?? '') ?>" maxlength="50">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label">Estado</label>
                        <input type="text" class="form-control border-dark-subtle" name="estado" id="estado"
                               value="<?= htmlspecialchars($dados->estado ?? '') ?>" maxlength="50">
                    </div>

                    <div class="col-md-4 mb-3 d-flex flex-column">
                        <div class="invisible">Confirmar</div>
                        <button type="submit" class="btn btn-primary mt-1 largura-50">Salvar Alterações</button>
                    </div>

                </div>
            </div>
            </form>
        </main>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const originalMenu = document.querySelector(".menu-lateral");
        const offcanvasContent = document.getElementById("offcanvasContent");

        if (originalMenu && offcanvasContent) {
            offcanvasContent.innerHTML = originalMenu.innerHTML;
        }
    });
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

                    // Foca no campo número
                    document.getElementById('numero').focus();
                } else {
                    alert('CEP não encontrado!');
                }
            });
    }
</script>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
