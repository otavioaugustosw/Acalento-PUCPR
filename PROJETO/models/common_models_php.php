<?php
// queries relacionadas ao COMMON
include_once (ROOT . "/php/auth_services/auth_service_php.php");

/**
 * Atualiza apenas a justificativa de uma punição para um usuário específico
 * e marca a punição como não revisada
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
 * @param int $punishment_id ID da punição.
 * @param string $justification Nova justificativa.
 * @param int $user_id ID do usuário que está fazendo a atualização.
 * @return bool Retorna true se a atualização for sucedida, false caso contrário.
 */
function update_punishment_justification(mysqli $conn, int $punishment_id, string $justification, int $user_id): bool
{
    try {
        $stmt = $conn->prepare("SELECT id FROM usuario_punicao WHERE id = ? AND id_usuario = ?");
        if (!$stmt) {
            throw new mysqli_sql_exception("erro na query " . $conn->error);
        }
        $stmt->bind_param("ii", $punishment_id, $user_id);
        $stmt->execute();
        $stmt = $stmt->get_result();
        if ($stmt->num_rows === 0) {
            $stmt->close();
            return false;
        }
        $stmt->close();

        $stmt = $conn->prepare("
            UPDATE usuario_punicao 
            SET justificativa = ?, revisado = 0 
            WHERE id = ? AND id_usuario = ?
        ");
        if (!$stmt) {
            throw new mysqli_sql_exception("erro na query " . $conn->error);
        }

        $stmt->bind_param(
            "sii",
            $justification,
            $punishment_id,
            $user_id
        );

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    } catch (mysqli_sql_exception $e) {
        // error_log("Erro em update_punishment_justification: " . $e->getMessage());
        return false;
    }
}


/**
 * Alterna o status de inatividade de uma punição de usuário.
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
 * @param int $punishment_id ID da punição na tabela usuario_punicao.
 * @return bool true em caso de sucesso, false em falha.
 */
function toggle_user_punishment_status(mysqli $conn, int $punishment_id, int $inativo): bool
{
    try {
        $update = "UPDATE usuario_punicao SET inativo = {$inativo}, revisado = 1 WHERE id = ?";
        $stmt = $conn->prepare($update);
        $stmt->bind_param("i", $punishment_id);
        return $stmt->execute();

    } catch (mysqli_sql_exception $e) {
        return false;
    }
}



/**
 * Obtém as punições de um usuário no banco de dados com base em uma condição.
 *
 * @param mysqli $conn Conexão com o banco de dados.
 * @param int $user_id ID do usuário cujas punições serão recuperadas.
 * @param string $where Condição adicional para a query (opcional).
 *
 * @return mysqli_result|false Retorna o resultado da consulta se bem-sucedido, ou false em caso de erro.
 */
function get_user_punishments(mysqli $conn, int $user_id, string $where)
{
    try {
        $stmt = $conn->prepare("
            SELECT up.*, e.nome AS nome_evento, e.data AS data_evento
            FROM usuario_punicao up
            LEFT JOIN evento e ON up.id_evento = e.id
            WHERE up.id_usuario = ? $where
            ORDER BY up.data_punicao DESC
        ");
        $stmt->bind_param(
            "i",
            $user_id
        );
        $punishments = $stmt->get_result();
        $stmt->close();
        return $punishments;
    } catch (mysqli_sql_exception $e) {
        // Logando o erro de forma simples e retornando false
        error_log("erro da query: " . $conn->error);
        return false;
    }
}

/**
 * Aplica a suspensão de um usuário.
 *
 * A suspensão é aplicada apenas se o número de punições do usuário for maior ou igual a 3.
 *
 * @param mysqli $conn Conexão com o banco de dados.
 * @param int $user_id ID do usuário a ser suspenso.
 *
 * @return bool|null Retorna true se a suspensão for aplicada com sucesso, false em caso de erro, ou null se o número de punições for menor que 3.
 */
function apply_user_suspension(mysqli $conn, $user_id, $admin_override = false)
{
    try {
        if ($admin_override) {
            $stmt = $conn->prepare("UPDATE usuario SET suspenso = 1 WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            return $stmt->execute();
        }

        $punishments = get_user_punishments($conn, $user_id, "AND inativo = 0");

        if ($punishments === false) {
            return null;
        }

        if ($punishments->num_rows < 3) {
            return null;
        }

        $stmt = $conn->prepare("UPDATE usuario SET suspenso = 1 WHERE id_= ?");
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

/**
 * Retira a suspensão de um usuário.
 *
 * @param mysqli $conn Conexão com o banco de dados.
 * @param int $user_id ID do usuário a ter a suspensão retirada.
 *
 * @return bool Retorna true se a suspensão for retirada com sucesso, false em caso de erro.
 */
function retire_user_suspension(mysqli $conn, int $user_id): bool
{
    try {
        $stmt = $conn->prepare("UPDATE usuario SET suspenso = 0 WHERE id = ?");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

/**
 * Verifica se um usuário está suspenso.
 *
 * @param mysqli $conn Conexão com o banco de dados.
 * @param int $user_id ID do usuário a ser verificado.
 *
 * @return bool|false Retorna true se o usuário estiver suspenso, false em caso de erro ou se o usuário não estiver suspenso.
 */
function is_user_suspended(mysqli $conn, int $user_id): bool
{
    try {
        $stmt = $conn->prepare("SELECT suspenso FROM usuario WHERE id = ?");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return (bool) $stmt->get_result()->fetch_object()->suspenso;
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

/**
 * Marca um usuário como inativo no banco de dados.
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
 * @param int $user_id ID do usuário a ser desativado.
 * @return bool Retorna true se a operação foi bem-sucedida, false caso contrário.
 */
function deactivate_user(mysqli $conn, int $user_id): bool
{
    try {
        $query = "UPDATE usuario SET inativo = 1 WHERE id = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            throw new mysqli_sql_exception("erro na query: " . $conn->error);
        }

        $stmt->bind_param("i", $user_id);

        if (!$stmt->execute()) {
            throw new mysqli_sql_exception("erro na query: " . $stmt->error);
        }

        return true;
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}


/**
 * Marca um usuário como inativo no banco de dados.
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
 * @param int $user_id ID do usuário a ser desativado.
 * @return bool Retorna true se a operação foi bem-sucedida, false caso contrário.
 */
function reactivate_user(mysqli $conn, int $user_id): bool
{
    try {
        $query = "UPDATE usuario SET inativo = 0 WHERE id = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            throw new mysqli_sql_exception("erro na query: " . $conn->error);
        }

        $stmt->bind_param("i", $user_id);

        if (!$stmt->execute()) {
            throw new mysqli_sql_exception("erro na query: " . $stmt->error);
        }

        return true;
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

/**
 * Atualiza a senha do usuário no banco de dados.
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
 * @param int $user_id ID do usuário cujo a senha será atualizada.
 * @param string $new_password Nova senha a ser configurada.
 * @return bool Retorna true se a senha foi atualizada com sucesso, caso contrário false.
 */
function update_password(mysqli $conn, int $user_id, string $new_password): bool
{
    try {
        $hashed_password = generate_password_hash($new_password);
        $query = "UPDATE usuario SET senha = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new mysqli_sql_exception("erro na query: " . $stmt->error);
        }
        $stmt->bind_param("si", $hashed_password, $user_id);  // "si" -> string e inteiro
        $result = $stmt->execute();
        return $result;
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

/**
 * Cria um novo endereço no banco de dados.
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
 * @param array $data Dados do formulário relacionados ao endereço.
 * @return int ID do endereço recém-criado.
 */
function create_address(mysqli $conn, array $data): int
{
    $cep = preg_replace('/\D/', '', $data['cep']);

    $query = "INSERT INTO endereco (cep, rua, numero, bairro, cidade, estado, complemento) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "ssissss",
        $cep,
        $data['rua'],
        $data['numero'],
        $data['bairro'],
        $data['cidade'],
        $data['estado'],
        $data['complemento']
    );

    $stmt->execute();

    return $conn->insert_id;
}

/**
 * Cria um novo usuário no banco de dados, vinculado a um endereço.
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
 * @param array $data Dados do formulário relacionados ao usuário.
 * @param int $address_id ID do endereço vinculado ao usuário.
 * @return void
 */
function create_user(mysqli $conn, array $data, int $address_id): bool
{
    try {
        $cpf = preg_replace('/\D/', '', $data['cpf']);
        $telefone = preg_replace('/\D/', '', $data['telefone']);
        $senha_hash = generate_password_hash($data['senha']);
        $nome = ucwords(strtolower($data['nome']));

        $query = "INSERT INTO usuario (id_endereco, email, senha, nome, cpf, telefone, nascimento)
              VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new mysqli_sql_exception("erro na query: " . $stmt->error);
        }
        $stmt->bind_param(
            "issssss",
            $address_id,
            $data['email'],
            $senha_hash,
            $nome,
            $cpf,
            $telefone,
            $data['nascimento']
        );

        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

/**
 * Recupera os dados de um usuário e seu respectivo endereço.
 *
 * @param mysqli $conn Conexão com o banco de dados.
 * @param int $user_id ID do usuário.
 * @return array|null Retorna os dados do usuário com endereço ou null em caso de erro.
 */
function get_user_data(mysqli $conn, int $user_id): ?object
{
    try {
        $query = "
            SELECT u.*, e.*
            FROM usuario u
            LEFT JOIN endereco e ON u.id_endereco = e.id
            WHERE u.id = ?
        ";

        $stmt = $conn->prepare($query);

        if (!$stmt) {
            throw new mysqli_sql_exception("erro na query: " . $stmt->error);
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_object();
    } catch (mysqli_sql_exception $e) {
        return null;
    }
}

/**
 * Atualiza os dados do usuário e do seu endereço.
 *
 * @param mysqli $conn Conexão com o banco de dados.
 * @param int $user_id ID do usuário.
 * @param int $address_id ID do endereço.
 * @param array $data Demais dados do formulário (sem os IDs).
 * @return bool true em caso de sucesso, false em erro.
 */
function update_user_and_address(mysqli $conn, int $user_id, int $address_id, array $data): bool
{
    try {
        $nome = ucwords(strtolower($data['nome']));
        $telefone = preg_replace('/\D/', '', $data['telefone']);
        $cpf = preg_replace('/\D/', '', $data['cpf']);
        $cep = preg_replace('/\D/', '', $_POST['cep']);

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
        $stmt->execute();

        $query = "UPDATE usuario
                  SET id_endereco = ?, email = ?, nome = ?, cpf = ?, telefone = ?, nascimento = ?
                  WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "isssssi",
            $address_id,
            $data['email'],
            $nome,
            $cpf,
            $telefone,
            $data['nascimento'],
            $user_id
        );
        return $stmt->execute();

    } catch (mysqli_sql_exception $e) {
        return false;
    }
}


/**
 * Verifica se já existe um usuário cadastrado com o e-mail ou CPF fornecidos.
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
 * @param string $email E-mail a ser verificado.
 * @param string $cpf CPF a ser verificado.
 * @return bool Retorna false se não existir e-mail nem CPF cadastrados. Encerra a execução com erro caso algum deles já exista.
 */
function verify_user_existence(mysqli $conn, string $email, string $cpf, int $user_id = 0): bool
{
    try {
        if (check_field_exists($conn, 'usuario', 'email', $email, $user_id)) {
            showError(16);
            exit();
        }

        if (check_field_exists($conn, 'usuario', 'cpf', $cpf, $user_id)) {
            showError(19);
            exit();
        }

        return false;

    } catch (Exception $e) {
        showError(15);
        exit();
    }
}
// MARK _
/**
 * Verifica se já existe um valor em determinado campo de uma tabela.
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
 * @param string $table Nome da tabela.
 * @param string $field Nome do campo.
 * @param string $value Valor a ser procurado.
 * @return bool Retorna true se o valor já existir, false caso contrário.
 */
function check_field_exists(mysqli $conn, string $table, string $field, string $value, int $user_id): bool
{
    $query = "SELECT 1 FROM {$table} WHERE {$field} = ? AND usuario.id != ? LIMIT 1";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Erro ao preparar a query para verificar {$field}.");
    }

    $stmt->bind_param("si", $value, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows > 0;
}

function get_users_where($conn, $where)
{
    $query = "
        SELECT id, nome, telefone, email, cpf, eh_doador, eh_voluntario, eh_adm, inativo, suspenso
        FROM usuario
        $where";
    return $conn->query($query);
}