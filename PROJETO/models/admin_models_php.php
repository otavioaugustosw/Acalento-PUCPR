<?php
// queries relacionadas ao ADMIN

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
function create_event(mysqli $conn, array $data): bool
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
            $data['link_media']
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
function update_event(mysqli $conn, array $data, int $event_id): bool
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
            "issssisi",
            $data['id_assentamento'],
            $data['nome'],
            $data['descricao'],
            $data['data'],
            $data['hora'],
            $data['lotacao_max'],
            $data['link_media'],
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
        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

/**
 * Cria uma nova campanha de doação no banco de dados.
 *
 * @param mysqli $conn Conexão ativa com o banco de dados.
 * @param string $name Nome da campanha.
 * @param string $date Data da campanha (formato yyyy-mm-dd).
 * @param int $destination_event_id ID do evento de destino.
 *
 * @return void
 */
function create_donation_campaign(mysqli $conn, string $name, string $date, int $destination_event_id): bool
{
    try {
        $query = "
            INSERT INTO campanha_doacao (nome, data, evento_destino)
            VALUES (?, ?, ?)
        ";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $name, $date, $destination_event_id);
        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}