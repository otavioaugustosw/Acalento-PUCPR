<?php
const PAGE_ERROR = [
    1=> "Não possui acesso administrador",
    2=> "Página não existente",
    3=> "Não foi possível criar a campanha",
    4=> "Não foi possível criar o evento",
    5=> "Não foi possível salvar a doação, tente novamente",
    6=> "Erro ao deletar o evento, tente novamente mais tarde",
    7=> "Algo deu errado, tente novamente mais tarde",
    8=> "Não foi possível alterar o evento, tente novamente mais tarde",
    9=> "Nenhum evento selecionado",
    10=> "Nenhuma campanha selecionada",
    11=> "Usuário não logado",
    12=> "Não foi possível cancelar a sua participação, tente de novo mais tarde",
    13=> "Evento lotado",
    14=> "Não foi possível realizar a inscrição, tente novamente mais tarde"

];


const PAGE_SUCCESS = [
    1=> "Campanha salva com sucesso",
    2=> "Evento criado com sucesso",
    3=> "Doação salva com sucesso",
    4=> "Evento deletado com sucesso",
    5=> "Evento alterado com sucesso",
    6=> "Participação cancelada com sucesso",
    7=> "Inscrição feita com sucesso",
    8=> "Evento deletado com sucesso",

    9=> "Usuário cadastrado com sucesso",
    10=> "Senha alterada com sucesso"
];

function showError($pageNum)
{
    ?>
    <div class="toast-container position-absolute p-3 top-0 start-50 translate-middle-x">
        <div class="toast text-bg-danger border-0 show" id="toastRuim">
            <div class="d-flex">
                <div class="toast-body">
                    <?= isset(PAGE_ERROR[$pageNum]) ? PAGE_ERROR[$pageNum] : "Acão inexistente" ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fechar"></button>
            </div>
        </div>
    </div>
    <?php
}

function showSucess($pageNum)
{
    ?>
    <div class="toast-container position-absolute p-3 top-0 start-50 translate-middle-x">
        <div class="toast text-bg-success border-0 show" id="toastBom">
            <div class="d-flex">
                <div class="toast-body">
                    <?= isset(PAGE_SUCCESS[$pageNum]) ? PAGE_SUCCESS[$pageNum] : "Acão inexistente" ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fechar"></button>
            </div>
        </div>
    </div>
    <?php
}