<?php
function get_donations_where($conn, $where)
{
    $query = "
            SELECT doacao.*,
            usuario.nome AS usuario_nome,
            opcao_item_doacao.nome AS opcao_nome,
            campanha_doacao.nome AS campanha_doacao_nome
            FROM doacao
            LEFT JOIN usuario ON doacao.id_usuario = usuario.id
            LEFT JOIN opcao_item_doacao ON doacao.id_opcao_item_doacao = opcao_item_doacao.id
            LEFT JOIN campanha_doacao ON doacao.id_campanha_doacao = campanha_doacao.id
            $where
            ";

    return $conn->query($query);
}

function get_campaigns_where($conn, $where = null)
{
    $query = "
            SELECT campanha_doacao.*,
            assentamento.nome AS assentamento_nome
            FROM campanha_doacao
            LEFT JOIN assentamento ON campanha_doacao.evento_destino = assentamento.id
            $where 
            ";
    return $conn->query($query);
}