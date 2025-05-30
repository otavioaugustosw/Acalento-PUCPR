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

function get_donations_log_where(mysqli $conn, ?string $where = null)
{
    try {
        // Definir a query com a parte WHERE opcional
        $query = "
            SELECT
                d.id as id_doacao,
                d.data,
                d.categoria,
                d.id_opcao_item_doacao,
                d.id_estoque as id_estoque_doacao,
                d.quantidade,
                d.unidade_medida,
                a.id as id_assentamento,
                a.nome as nome_assentamento,
                a.familias,
                e.id as id_estoque,
                e.nome as nome_estoque,
                oid.id as id_opcao,
                oid.nome as nome_opcao
            FROM doacao d
            JOIN acalento.estoque e on e.id = d.id_estoque
            JOIN acalento.opcao_item_doacao oid on d.id_opcao_item_doacao = oid.id
            JOIN acalento.assentamento a on a.id = e.id_assentamento;
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


function get_item_quantity_inventory(mysqli $conn, int $id_item)
{
    try {
        $query = "
SELECT
    e.id AS id_estoque,
    e.nome AS nome_estoque,
    a.id AS id_assentamento,
    a.nome AS nome_assentamento,
    a.familias quantidade_familias,
    oid.id AS item_id,
    oid.nome AS item_nome,
    COALESCE(MAX(d.unidade_medida), 'u') AS unidade,
    COALESCE(SUM(d.quantidade), 0) AS total_item

FROM (
    SELECT * FROM acalento.assentamento
) a
CROSS JOIN (
    SELECT * FROM acalento.opcao_item_doacao WHERE id = ?
) oid
INNER JOIN acalento.estoque e on e.id = a.id_estoque
LEFT JOIN acalento.doacao d ON d.id_estoque = e.id AND d.id_opcao_item_doacao = oid.id
GROUP BY    e.id, e.nome, a.id, a.nome, a.familias, oid.id, oid.nome;
        ";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new mysqli_sql_exception("erro da query: " . $conn->error);
        }

        $stmt->bind_param("i", $id_item);
        $stmt->execute();
        return $stmt->get_result();
    } catch (mysqli_sql_exception $e) {

        var_dump($e->getMessage());
        return false;
    }
}

/**
 * Registra uma nova doação e marca o usuário como doador, se aplicável.
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
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

function get_donations_to_validate(mysqli $conn, $where)
{
    try {
        $query = "
        SELECT doacao_monetaria.*,
        usuario.nome AS usuario_nome
        FROM doacao_monetaria
        JOIN usuario ON doacao_monetaria.id_usuario = usuario.id
        $where";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new mysqli_sql_exception("erro da query: " . $conn->error);
        } $stmt->execute();

        return $stmt->get_result();

    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

function validate_donation(mysqli $conn, int $status, int $id_donation)
{
    try {
        $query = "
        UPDATE doacao_monetaria SET validado = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new mysqli_sql_exception("erro da query: " . $conn->error);
        }

        $stmt->bind_param("ii", $status, $id_donation);
        return $stmt->execute();

    } catch (mysqli_sql_exception $e) {
        return false;
    }
}