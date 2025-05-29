<?php
// queries relacionadas ao ADMIN

/**
 * Adiciona uma punição para um usuário que esteve em um evento ou não.
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
 * @param int $user_id ID do usuário a ser punido.
 * @param string $reason Motivo da punição.
 * @param string|null $justification Justificativa da punição (opcional).
 *
 * @param int|null $event_id ID do evento no qual o usuário será punido.
 * @return bool Retorna true se a punição foi adicionada com sucesso, ou false em caso de erro.
 */
function add_user_punishment(mysqli $conn, int $user_id, string $reason, ?string $justification = null, ?int $event_id = null)
{
    try {
        $stmt = $conn->prepare("
            INSERT INTO usuario_punicao (id_usuario, id_evento, motivo, justificativa)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "iiss",
            $user_id,
            $event_id,
            $reason,
            $justification
        );

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

/**
 * Cria um novo usuário admin no banco de dados, vinculado a um endereço.
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
 * @param array $data Dados do formulário relacionados ao usuário.
 * @param int $address_id ID do endereço vinculado ao usuário.
 * @return void
 */
function create_user_admin(mysqli $conn, array $data, int $address_id): bool
{
    try {
        $cpf = preg_replace('/\D/', '', $data['cpf']);
        $telefone = preg_replace('/\D/', '', $data['telefone']);
        $senha_hash = generate_password_hash($data['senha']);
        $nome = ucwords(strtolower($data['nome']));
        $eh_adm = 1;

        $query = "INSERT INTO usuario (id_endereco, email, senha, nome, cpf, telefone, nascimento, eh_adm)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new mysqli_sql_exception("erro na query: " . $stmt->error);
        }
        $stmt->bind_param(
            "issssssi",
            $address_id,
            $data['email'],
            $senha_hash,
            $nome,
            $cpf,
            $telefone,
            $data['nascimento'],
            $eh_adm
        );

        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        return false;
    }

}

/**
 * Cria um novo evento no banco de dados a partir de um array associativo.
 *
 * Espera-se que o array $data contenha as seguintes chaves:
 * - 'id_assentamento' (int)
 * - 'nome' (string)
 * - 'descricao' (string)
 * - 'lotacao_max' (int)
 * - 'data' (string, formato YYYY-MM-DD)
 * - 'hora' (string, formato HH:MM)
 * - 'link_media' (string)
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
 * @param array $data Dados do evento.
 * @return bool Retorna true se o evento foi criado com sucesso, false caso contrário.
 */
function create_event(mysqli $conn, array $data, $image_path): bool
{
    try {
        $query = "
            INSERT INTO evento (
                id_assentamento, nome, descricao, lotacao_max, data, hora, link_media
            ) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($query);

        if (!$stmt) {
            throw new mysqli_sql_exception("erro na query" . $stmt->error);
        }

        $stmt->bind_param(
            "ississs",
            $data['id_assentamento'],
            $data['nome'],
            $data['descricao'],
            $data['lotacao_max'],
            $data['data'],
            $data['hora'],
            $image_path
        );

        if (!$stmt->execute()) {
            throw new mysqli_sql_exception("erro na query" . $stmt->error);
        }

        return true;
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

/**
 * Atualiza os dados de um evento no banco de dados.
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
 * @param array $data Dados do formulário via $_POST.
 * @param int $event_id ID do evento a ser atualizado.
 * @return bool Verdadeiro em caso de sucesso.
 */
function update_event(mysqli $conn, array $data, int $event_id, string $image_path): bool
{
    try {
        $query = "UPDATE evento 
                  SET id_assentamento = ?, 
                      nome = ?, 
                      descricao = ?, 
                      data = ?, 
                      hora = ?, 
                      lotacao_max = ?, 
                      link_media = ? 
                  WHERE id = ?";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("erro na query: " . $conn->error);
        }

        $stmt->bind_param(
            "issssssi",
            $data['id_assentamento'],
            $data['nome'],
            $data['descricao'],
            $data['data'],
            $data['hora'],
            $data['lotacao_max'],
            $image_path,
            $event_id
        );

        if (!$stmt->execute()) {
            throw new Exception("erro na query: " . $stmt->error);
        }
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Marca um evento como inativo (soft delete) com base no ID.
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
 * @param int $event_id ID do evento a ser marcado como inativo.
 * @return bool Retorna true se a atualização foi bem-sucedida, false caso contrário.
 */
function soft_delete_event(mysqli $conn, int $event_id): bool {
    $query = "UPDATE evento SET inativo = 1 WHERE id = ?";

    try {
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        return true;
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}


/**
 * Obtém todas as punições com detalhes do usuário e evento, para a tela de admin.
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
 * @param string $where_conditions Condições SQL para o filtro (ex: "WHERE u.nome LIKE '%fulano%' AND up.inativo = 0").
 * Se não vazio, deve começar com "WHERE".
 * @return mysqli_result|false Retorna o resultado da consulta, ou false em caso de erro.
 */
function get_all_punishments(mysqli $conn, string $where = ""): mysqli_result|false
{
    try {
        $query = "
            SELECT 
                up.id AS punicao_id,
                up.motivo,
                up.justificativa,
                up.data_punicao,
                up.inativo,
                up.revisado,
                u.id AS usuario_id,
                u.nome AS usuario_nome,
                u.email AS usuario_email,
                e.id AS evento_id,
                e.nome AS evento_nome,
                e.data AS evento_data
            FROM usuario_punicao up
            JOIN usuario u ON up.id_usuario = u.id
            LEFT JOIN evento e ON up.id_evento = e.id
             $where  
            ORDER BY up.data_punicao DESC
        ";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new mysqli_sql_exception("erro na query" . $conn->error);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;

    } catch (mysqli_sql_exception $e) {
        return false;
    }
}


function get_event_voluntary(mysqli $conn, $event_id, $extra_where = ""): bool | mysqli_result
{
    try {
        $query = "
                    SELECT
                u.nome as usuario_nome,
                u.id as usuario_id,
                upe.participacao_confirmada AS confirmacao,
                upe.presenca,
                e.nome as evento_nome,
                e.data as evento_data
                FROM usuario_participa_evento upe
                JOIN usuario u ON upe.id_usuario = u.id
                JOIN acalento.evento e on e.id = upe.id_evento
                WHERE id_evento = ? AND upe.participacao_confirmada $extra_where 
        ";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new mysqli_sql_exception("erro da query: " . $conn->error);
        }

        $stmt->bind_param("i", $event_id);
        $stmt->execute();

        return $stmt->get_result();
    } catch (mysqli_sql_exception $e) {
        showError(20);
        return false;
    }
}

function end_event(mysqli $conn, int $event_id): bool {

    try {
        $volunteers = get_event_voluntary($conn, $event_id, " AND !upe.presenca");
        if ($volunteers->num_rows > 0) {
            while ($voluntary = $volunteers->fetch_object()) {
                add_user_punishment($conn, $voluntary->usuario_id, "Faltou no evento $voluntary->evento_nome");
            }
        }
        $query = "UPDATE evento SET finalizado = 1 WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $event_id);
        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

function register_donation_monetary(mysqli $conn, $id_usuario, $valor, $data, $caminho , $validado)
{
    try {
        $query = "
        INSERT INTO doacao_monetaria(id_usuario, valor, data, link_media, validado)
        VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("idssi", $id_usuario, $valor, $data, $caminho , $validado);
        $stmt->execute();
        if ($id_usuario != null) {
            $query = "UPDATE usuario SET eh_doador = 1 WHERE id = ?";
            load_user_session_data($conn);
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
        }
        showSucess(3);

    } catch (mysqli_sql_exception $e) {
        echo $e->getMessage();
    }
}


function create_inventory($conn, array $data) {

    $nome = ucwords(strtolower("Estoque " . $data['nome']));

    $query = "
    INSERT INTO estoque(nome) 
    VALUES (?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $nome);

    $stmt->execute();
    return $conn->insert_id;
}

function create_settlement($conn, array $data, $id_endereco, $id_estoque) {
    try {
        $nome = ucwords(strtolower($data['nome']));

        $query = "
        INSERT INTO assentamento(id_endereco, nome, familias, id_estoque) 
        VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isii",$id_endereco, $nome, $data['familias'], $id_estoque);

        if (!$stmt->execute()) {
            throw new mysqli_sql_exception("Erro na execução da query: " . $stmt->error);
        }
        return true;

    } catch (mysqli_sql_exception $e) {
        error_reporting(E_ALL);
        return false;
    }
}


function get_settlement_info(mysqli $conn, int $id): ?stdClass
{
    try {
        $stmt = $conn->prepare("
            SELECT 
                a.id, 
                a.nome, 
                a.familias, 
                a.id_endereco, 
                a.id_estoque,
                e.cep, e.rua, e.numero, e.bairro, e.cidade, e.estado, e.complemento
            FROM assentamento a
            JOIN endereco e ON a.id_endereco = e.id
            WHERE a.id = ?
        ");

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_object();
    } catch (mysqli_sql_exception $e) {
        return null;
    }
}


function get_settlements_info(mysqli $conn): ? mysqli_result
{
    try {
        $query = "SELECT 
                a.id, 
                a.nome, 
                a.familias, 
                a.id_endereco, 
                a.id_estoque,
                e.rua, e.numero
              FROM assentamento a
              JOIN endereco e ON a.id_endereco = e.id
              WHERE a.inativo = 0";
        return $conn->query($query);
    } catch (mysqli_sql_exception $e) {
        return null;
    }
}

function update_address_inventory_settlement(mysqli $conn, array $data, int $address_id, int $inventory_id, int $settlement_id): bool {
    try {
        $cep = preg_replace('/\D/', '', $data['cep']);
        $query = "UPDATE endereco
                  SET cep = ?, rua = ?, numero = ?, bairro = ?, cidade = ?, estado = ?, complemento = ?
                  WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "ssissssi",
            $cep,
            $data['rua'],
            $data['numero'],
            $data['bairro'],
            $data['cidade'],
            $data['estado'],
            $data['complemento'],
            $address_id
        );
        if (!$stmt->execute()) return false;
        $stmt->close();


        $query = "UPDATE estoque 
                  SET nome = ? 
                  WHERE id = ?";
        $stmt = $conn->prepare($query);
        $nomeEstoque = ucwords(strtolower("Estoque " . $data['nome']));
        $stmt->bind_param("si", $nomeEstoque, $inventory_id);
        if (!$stmt->execute()) return false;
        $stmt->close();

        $query = "UPDATE assentamento 
                  SET id_endereco = ?, 
                      nome = ?, 
                      familias = ?, 
                      id_estoque = ? 
                  WHERE id = ?";
        $stmt = $conn->prepare($query);
        $nomeAssentamento = ucwords(strtolower($data['nome']));
        $stmt->bind_param(
            "isiii",
            $address_id,
            $nomeAssentamento,
            $data['familias'],
            $inventory_id,
            $settlement_id
        );
        if (!$stmt->execute()) return false;
        $stmt->close();

        return true;
    } catch (Exception $e) {
        return false;
    }
}

function soft_delete_inventory_settlement(mysqli $conn, int $inventory_id, int $settlement_id): bool {
    try {
        $query = "UPDATE estoque SET inativo = 1 WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $inventory_id);
        $stmt->execute();

        $query = "UPDATE assentamento SET inativo = 1 WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $settlement_id);
        $stmt->execute();

        return true;
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}
