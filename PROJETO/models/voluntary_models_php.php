<?php

/**
 * Recupera eventos com base em uma cláusula WHERE customizada,
 * incluindo informações sobre local, participantes e status do usuário logado.
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
 * @param string $where Cláusula WHERE adicional para filtrar os eventos.
 * @param int $user_id ID do usuário atualmente autenticado.
 *
 * @return mysqli_result|false Resultado da consulta em caso de sucesso, ou false em caso de falha.
 */
function get_events_where(mysqli $conn, string $where, int $user_id): mysqli_result|false
{
    try {
        $query = "
            SELECT
                evento.*,
                endereco.rua,
                endereco.cidade,
                endereco.bairro,
                endereco.cep,
                endereco.numero,
                endereco.complemento,
                assentamento.nome AS assentamento_nome,
                (SELECT COUNT(*) FROM usuario_participa_evento WHERE id_evento = evento.id) AS inscritos,
                CASE
                    WHEN upe.participacao_confirmada IS NOT NULL THEN 1
                    ELSE 0
                END AS esta_inscrito,
                upe.participacao_confirmada AS confirmacao,
                upe.presenca
            FROM evento
            LEFT JOIN assentamento ON evento.id_assentamento = assentamento.id
            LEFT JOIN endereco ON assentamento.id_endereco = endereco.id
            LEFT JOIN usuario_participa_evento upe 
                ON upe.id_evento = evento.id AND upe.id_usuario = ? 
            $where
        ";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new mysqli_sql_exception("erro da query: " . $conn->error);
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        return $stmt->get_result();
    } catch (mysqli_sql_exception $e) {
        showError(20);
        return false;
    }
}

/**
 * Alterna a participação voluntária do usuário em um evento (confirmar ou desconfirmar).
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
 * @param int $user_id ID do usuário.
 * @param int $event_id ID do evento.
 *
 * @return bool Retorna true em caso de sucesso, false caso contrário.
 */
function toggle_voluntary_participation_event(mysqli $conn, int $user_id, int $event_id): bool
{
    try {
        $query = "
            UPDATE usuario_participa_evento 
            SET participacao_confirmada = !participacao_confirmada
            WHERE id_usuario = ? AND id_evento = ?
        ";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new mysqli_sql_exception("erro da query: " . $conn->error);
        }

        $stmt->bind_param("ii", $user_id, $event_id);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

/**
 * Alterna a presença voluntária do usuário em um evento (presente ou ausente).
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
 * @param int $user_id ID do usuário.
 * @param int $event_id ID do evento.
 *
 * @return bool Retorna true em caso de sucesso, false caso contrário.
 */
function toggle_voluntary_presence_event(mysqli $conn, int $user_id, int $event_id): bool
{
    try {
        $query = "
            UPDATE usuario_participa_evento 
            SET presenca = !presenca
            WHERE id_usuario = ? AND id_evento = ?
        ";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new mysqli_sql_exception("erro da query: " . $conn->error);
        }

        $stmt->bind_param("ii", $user_id, $event_id);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

/**
 * Recupera o resultado da consulta de todos os assentamentos.
 *
 * O resultado deve ser tratado com fetch_object() na página que invoca esta função.
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
 * @return mysqli_result|null Resultado da consulta ou null em caso de falha.
 */
function get_all_settlements(mysqli $conn): ?mysqli_result
{
    try {
        $query = "SELECT id, nome FROM assentamento";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        return $stmt->get_result();

    } catch (mysqli_sql_exception $e) {
        return null;
    }
}

/**
 * Remove um usuário específico de um evento.
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
 * @param int $event_id ID do evento.
 * @param int $user_id ID do usuário a ser removido do evento.
 * @return bool True se a operação foi bem-sucedida, false caso contrário.
 */
function cancel_user_event_subscription(mysqli $conn, int $event_id, int $user_id): bool
{
    try {
        $query = "DELETE FROM usuario_participa_evento WHERE id_evento = ? AND id_usuario = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            throw new mysqli_sql_exception("erro na query " . $conn->error);
        }
        $stmt->bind_param("ii", $event_id, $user_id);
        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}