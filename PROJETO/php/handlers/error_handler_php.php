<?php
include (ROOT . '/components/popup/popup.php');
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
    14=> "Não foi possível realizar a inscrição, tente novamente mais tarde",
    15=> "Não foi possível realizar o cadastro, tente novamente mais tarde",
    16=> "O e-mail já foi utilizado em outro cadastro",
    17=> "As senhas não estão iguais",
    18=> "Não foi possível atualizar as ediçes, tente novamente mais tarde",
    19=> "O CPF já foi utilizado em outro cadastro",
    20=> "Erro ao se comunicar com a nossa base da dados",
    21=> "Erro ao confirmar presença",
    22=> "Cancelamos sua inscrição porém uma punição foi aplicada",
    23=> "Sua conta foi deletada, contate um administrador caso queira utiliza-la novamente",
    24=> "Sua conta está suspensa, contate um administrador"
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
    10=> "Senha alterada com sucesso",
    20=> "Perfil inativado com sucesso",
    21=> "Presença confirmada com sucesso!",
];

function showError($error_num)
{
make_red_popup(PAGE_ERROR[$error_num] ?? "Acão inexistente");
}

function showSucess($sucess_num)
{
make_green_popup(PAGE_SUCCESS[$sucess_num] ?? "Acão inexistente");
}