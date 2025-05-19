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
                opcao_item_doacao.nome AS opcao_nome
            FROM doacao
            LEFT JOIN usuario ON doacao.id_usuario = usuario.id
            LEFT JOIN opcao_item_doacao ON doacao.id_opcao_item_doacao = opcao_item_doacao.id
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
//function get_campaigns_where(mysqli $conn, ?string $where = null)
//{
//    try {
//        // Definir a query com a parte WHERE opcional
//        $query = "
//            SELECT campanha_doacao.*,
//                assentamento.nome AS assentamento_nome
//            FROM campanha_doacao
//            LEFT JOIN assentamento ON campanha_doacao.evento_destino = assentamento.id
//            $where
//        ";
//
//        $stmt = $conn->prepare($query);
//        if (!$stmt) {
//            throw new mysqli_sql_exception("erro da query: " . $conn->error);
//        }
//
//        // Caso haja um where, executamos a consulta
//        $stmt->execute();
//
//        return $stmt->get_result();
//    } catch (mysqli_sql_exception $e) {
//        return false;
//    }
//}

/**
 * Registra uma nova doação e marca o usuário como doador, se aplicável.
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
 * @param int $campaign_id ID da campanha de doação.
 * @param int $stock_id ID do estoque associado.
 * @param int $user_id ID do usuário (ou null se não logado).
 * @param array $data Dados da doação (id_opcao_item_doacao, quantidade, unidade_medida, categoria, data).
 * @return bool true em caso de sucesso, false caso contrário.
 */
function create_material_donation(
    mysqli $conn,
    ?int $stock_id,
    int $user_id,
    array $data
): bool {
    try {
        $query = "
            INSERT INTO doacao (
                id_estoque, id_opcao_item_doacao, id_usuario,
                quantidade, unidade_medida, categoria, data
            ) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "iiissss",
            $stock_id,
            $data['id_opcao_item_doacao'],
            $user_id,
            $data['quantidade'],
            $data['unidade_medida'],
            $data['categoria'],
            $data['data']
        );
        $stmt->execute();

        if (!is_null($user_id)) {
            $updateQuery = "UPDATE usuario SET eh_doador = 1 WHERE id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("i", $user_id);
            $updateStmt->execute();
        }

        return true;

    } catch (mysqli_sql_exception $e) {
        var_dump($e);
        return false;
    }
}

function get_donations_where_badges(mysqli $conn, int $user_id)
{
    try {
        $query = "
            SELECT
                (SELECT COUNT(*) FROM doacao WHERE id_usuario = ?) +
                (SELECT COUNT(*) FROM doacao_monetaria WHERE id_usuario = ?)
                AS total
        ";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new mysqli_sql_exception("erro da query: " . $conn->error);
        }

        $stmt->bind_param("ii", $user_id, $user_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $total = $result->fetch_object();

        return (int) $total->total;
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

function get_last_donation(mysqli $conn, int $user_id)
{
    try {
        $query = "
            SELECT DATEDIFF(CURDATE(), ultima_doacao) AS dias_sem_doar
            FROM (SELECT MAX(data_doacao) AS ultima_doacao
                FROM (SELECT data AS data_doacao FROM doacao WHERE id_usuario = ?
                UNION ALL
                SELECT data AS data_doacao FROM doacao_monetaria WHERE id_usuario = ?) AS todas
                ) AS resultado
        ";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new mysqli_sql_exception("erro da query: " . $conn->error);
        }

        $stmt->bind_param("ii", $user_id, $user_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $total_dias = $result->fetch_object();

        return (int) $total_dias-> dias_sem_doar;
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}