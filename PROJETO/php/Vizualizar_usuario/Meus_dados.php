<?php
// CHUNCHO TEMPORÁRIO - ID fixo para desenvolvimento
$id_usuario = 12;
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../conexao.php";
$conexao = conecta_db();

// Consulta para pegar os dados do usuário
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
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../css/default/default.css">
    <link rel="stylesheet" href="../Cadastro_usuario/Cadastro_usuario.css">
    <link rel="stylesheet" href="../../components/forms/form-style.css">
    <link rel="stylesheet" href="../../components/sidebars/sidebars.css">
    <link rel="stylesheet" href="../../css/default/main.css">



    <title>Meus Dados</title>
</head>
<body>
<?php include("../../../PROJETO/components/sidebars/sidebar-mobile.php") ?>
<div class="d-flex flex-nowrap">
    <?php include("../../../PROJETO/components/sidebars/sidebars.php") ?>

    <!-- conteúdo principal -->
    <div class="main">
        <main class="px-5 row align-items-center">
            <div class="container-fluid">
                <h2 class="my-4">Meus Dados</h2>

                <!-- Dados Pessoais -->
                <div class="mb-4">
                    <h5 class="mb-3">Informações Pessoais</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nome Completo</label>
                            <div class="form-control border-dark-subtle"><?= htmlspecialchars($dados->nome ?? 'Não informado') ?></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <div class="form-control border-dark-subtle"><?= htmlspecialchars($dados->email ?? 'Não informado') ?></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">CPF</label>
                            <div class="form-control border-dark-subtle"><?= htmlspecialchars($dados->cpf ?? 'Não informado') ?></div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Telefone</label>
                            <div class="form-control border-dark-subtle"><?= htmlspecialchars($dados->telefone ?? 'Não informado') ?></div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Data de Nascimento</label>
                            <div class="form-control border-dark-subtle"><?= htmlspecialchars($dados->nascimento ?? 'Não informado') ?></div>
                        </div>
                    </div>
                </div>

                <!-- Endereço -->
                <div class="mb-4">
                    <h5 class="mb-3">Endereço</h5>

                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label class="form-label">CEP</label>
                            <div class="form-control border-dark-subtle"><?= htmlspecialchars($dados->cep ?? 'Não informado') ?></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Logradouro</label>
                            <div class="form-control border-dark-subtle"><?= htmlspecialchars($dados->rua ?? 'Não informado') ?></div>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">Número</label>
                            <div class="form-control border-dark-subtle"><?= htmlspecialchars($dados->numero ?? 'Não informado') ?></div>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">Bairro</label>
                            <div class="form-control border-dark-subtle"><?= htmlspecialchars($dados->bairro ?? 'Não informado') ?></div>
                        </div>
                    </div>

                    <div class="row align-items-center">  <!-- Alterado para align-items-center -->
                        <!-- Cidade -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Cidade</label>
                            <div class="form-control border-dark-subtle"><?= htmlspecialchars($dados->cidade ?? 'Não informado') ?></div>
                        </div>

                        <!-- Estado -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Estado</label>
                            <div class="form-control border-dark-subtle"><?= htmlspecialchars($dados->estado ?? 'Não informado') ?></div>
                        </div>

                        <!-- Botão de editar -->
                        <div class="col-md-2 mb-3 d-flex flex-column">
                            <div class="invisible">Confirmar</div>
                            <!-- Texto invisível que mantém o espaço -->
                            <a href="../Editar_usuario/editar_usuario.php?id_usuario=<?= $id_usuario ?>"
                               class="btn btn-primary  align-self-end w-100 h-100">Editar Dados</a>
                        </div>


                        <!-- Botão de deletar -->
                        <div class="col-md-2 mb-3 d-flex flex-column">
                            <div class="invisible">Confirmar</div>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmarModal">
                            Inativar Conta</button>
                        <!-- Modal de confirmação -->
                        <div class="modal fade" id="confirmarModal" tabindex="-1" aria-labelledby="confirmarModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="../deletar_usuario/Deletar_usuario.php">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmarModalLabel">Confirmar Inativação</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                        </div>
                                        <div class="modal-body">
                                            Tem certeza que deseja inativar sua conta?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" name="inativar" class="btn btn-danger">Confirmar</button>


                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>