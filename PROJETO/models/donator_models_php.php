<?php
/**
 * Obtém as doações com base em um critério de filtro.
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
 * @param string $where Critério de filtro para a consulta
 *
 * @return mysqli_result|false Retorna o resultado da consulta, ou false em caso de erro.
 */
function get_donations_where(mysqli $conn, string $where)
{
    try {
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

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new mysqli_sql_exception("erro da query: " . $conn->error);
        }
        $stmt->execute();

        return $stmt->get_result();
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

/**
 * Obtém campanhas de doação com base em um critério de filtro.
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
 * @param string|null $where Critério de filtro para a consulta (opcional).
 *
 * @return mysqli_result|false Retorna o resultado da consulta, ou false em caso de erro.
 */
function get_campaigns_where(mysqli $conn, ?string $where = null)
{
    try {
        // Definir a query com a parte WHERE opcional
        $query = "
            SELECT campanha_doacao.*,
                assentamento.nome AS assentamento_nome
            FROM campanha_doacao
            LEFT JOIN assentamento ON campanha_doacao.evento_destino = assentamento.id
            $where
        ";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new mysqli_sql_exception("erro da query: " . $conn->error);
        }

        // Caso haja um where, executamos a consulta
        $stmt->execute();

        return $stmt->get_result();
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}