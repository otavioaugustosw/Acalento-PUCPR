<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include (ROOT . "/php/config/database_php.php");

$conexao = connectDatabase();
$id_usuario = $_SESSION["USER_ID"];

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
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/sidebars.css">
    <link rel="stylesheet" href="css/form-style.css">
    <link rel="stylesheet" href="css/main-content.css">



    <title>Meus Dados</title>
</head>
<body>
<?php
if (isset($_GET['error'])) {
    showError($_GET['error']);
}

if (isset($_GET['success'])) {
    showSucess($_GET['success']);
}
?>
<?php include(ROOT . "/components/sidebars/sidebar-mobile.php") ?>
<div class="d-flex flex-nowrap">
    <?php include(ROOT .  "/components/sidebars/sidebars.php") ?>
    </div>

    <div class="flex-grow-1 p-4 main-content">
        <main class="container-fluid align-content-center">
            <h2 class="my-4">Meus Dados</h2>


                <!-- Dados Pessoais -->
                <div class="mb-4">
                    <h5 class="mb-3">Informações Pessoais</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nome Completo</label>
                            <div class="form-control"><?= htmlspecialchars($dados->nome ?? 'Não informado') ?></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <div class="form-control "><?= htmlspecialchars($dados->email ?? 'Não informado') ?></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">CPF</label>
                            <div class="form-control "><?= htmlspecialchars($dados->cpf ?? 'Não informado') ?></div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Telefone</label>
                            <div class="form-control "><?= htmlspecialchars($dados->telefone ?? 'Não informado') ?></div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Data de Nascimento</label>
                            <div class="form-control "><?= htmlspecialchars($dados->nascimento ?? 'Não informado') ?></div>
                        </div>
                    </div>
                </div>

                <!-- Endereço -->
                <div class="mb-4">
                    <h5 class="mb-3">Endereço</h5>

                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label class="form-label">CEP</label>
                            <div class="form-control "><?= htmlspecialchars($dados->cep ?? 'Não informado') ?></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Logradouro</label>
                            <div class="form-control "><?= htmlspecialchars($dados->rua ?? 'Não informado') ?></div>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">Número</label>
                            <div class="form-control "><?= htmlspecialchars($dados->numero ?? 'Não informado') ?></div>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">Bairro</label>
                            <div class="form-control "><?= htmlspecialchars($dados->bairro ?? 'Não informado') ?></div>
                        </div>
                    </div>

                    <div class="row align-items-center">
                        <!-- Cidade -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Cidade</label>
                            <div class="form-control "><?= htmlspecialchars($dados->cidade ?? 'Não informado') ?></div>
                        </div>

                        <!-- Estado -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Estado</label>
                            <div class="form-control "><?= htmlspecialchars($dados->estado ?? 'Não informado') ?></div>
                        </div>

                        <!-- Botão de editar -->
                        <div class="col-md-2 mb-3 d-flex flex-column">
                            <!-- Texto invisível que mantém o espaço -->
                            <div class="invisible">Confirmar</div>
                            <a href="index.php?common=8&id_usuario=<?= $id_usuario ?>"
                               class="btn btn-primary align-self-end w-100 h-100">Editar Dados</a>
                        </div>

                        <!-- Botão de alterar a senha -->
                        <div class="col-md-2 mb-3 d-flex flex-column">
                            <!-- Texto invisível que mantém o espaço -->
                            <div class="invisible">Confirmar</div>
                            <a href="index.php?common=10&id_usuario=<?= $id_usuario ?>"
                               class="btn btn-primary align-self-end w-100 h-100">Mudar a senha</a>
                        </div>

                    </div>
                        <!-- Botão de deletar -->
                    <div class="row">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmarModal">
                                Inativar Conta
                            </button>
                        </div>
                    </div>
                        <!-- Modal (tipo popup mas com botão) de confirmação para inativar -->
                        <div class="modal fade" id="confirmarModal" tabindex="-1" aria-labelledby="confirmarModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="index.php?common=9">
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