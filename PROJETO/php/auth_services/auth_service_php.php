<?php
const MAX_LOGIN_ATTEMPTS = 5;
$_SESSION['LOGIN_ATTEMPTS'] = ($_SESSION['LOGIN_ATTEMPTS'] ?? 0);

const MESSAGES = [
    "BLOCK_USER" => ["status" => false, "statusName" => "BLOCK_USER"],
    "AUTH_FAILURE" => ["status" => false, "statusName" => "AUTH_FAILURE"],
    "FETCHING_DATA_ERROR" => ["status" => false, "statusName" => "FETCHING_DATA_ERROR"],
    "INVALID_CREDENTIALS" => ["status" => false, "statusName" => "INVALID_CREDENTIALS"],
    "PASSWORD_AUTHENTICATED" => ["status" => true, "statusName" => "PASSWORD_AUTHENTICATED"],
    "USER_FOUND" => ["status" => true, "statusName" => "USER_FOUND"],
    "USER_AUTHENTICATED" => ["status" => true, "statusName" => "USER_AUTHENTICATED"],
];

function authenticateUser($sql, $email, $typedPassword): array
{
    if ($_SESSION['LOGIN_ATTEMPTS'] >= MAX_LOGIN_ATTEMPTS) {
        sleep(3);
        return ["status" => false, "statusName" => "BLOCK"];
    }
    try {
        $userExists = _getUserByEmail($sql, $email);
        if (!$userExists["status"]) {
            _handleFailedAttempt();
            return $userExists;
        }
        $user = $userExists["user"];
        $passwordResult = _isPasswordCorrect($typedPassword, $user->senha);
        if (!$passwordResult["status"]) {
            _handleFailedAttempt();
            return $passwordResult;
        }
        session_regenerate_id(true);
        $_SESSION["USER_ID"] = $user->id;
        $_SESSION['LOGIN_ATTEMPTS'] = 0;
        return _loadUserSessionData($sql);
    } catch (mysqli_sql_exception $E) {
        return MESSAGES["AUTH_FAILURE"];
    }
}

function generatePasswordHash($typedPassword): string
{
    return password_hash($typedPassword . $_ENV['PEPPER_KEY'], PASSWORD_DEFAULT);
}

function verifyQueryResult($user): array
{
    if (!$user) {
        return MESSAGES["FETCHING_DATA_ERROR"];
    }
    return MESSAGES["USER_FOUND"];
}

function _handleFailedAttempt() {
    $_SESSION['LOGIN_ATTEMPTS'] = $_SESSION['LOGIN_ATTEMPTS'] + 1;
}

function _getUserByEmail($sql, $email): array
{
    $query = "SELECT id, senha FROM usuario WHERE email = ?";
    $stmt = $sql->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_object();
    $resultStatus = verifyQueryResult($user);
    if (!$resultStatus["status"]) {
        return $resultStatus;
    }
    return ["status"=>true,"user"=>$user];
}

function _isPasswordCorrect($password, $hashedPassword): array
{
    $pepperedPassword = $password . $_ENV['PEPPER_KEY'];
    if(password_verify($pepperedPassword, $hashedPassword)){
        return MESSAGES["PASSWORD_AUTHENTICATED"];
    } else {
        return MESSAGES["INVALID_CREDENTIALS"];
    }
}

function _loadUserSessionData($sql): array
{
    $query = "SELECT email, nome, eh_doador, eh_adm, eh_voluntario, id_endereco FROM usuario WHERE id = ?;";
    $stmt = $sql->prepare($query);
    $stmt->bind_param("i", $_SESSION['USER_ID']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_object();
    $resultStatus = verifyQueryResult($user);
    if (!$resultStatus["status"]) {
        return $resultStatus;
    }
    var_dump($user);
    $_SESSION['USER_EMAIL'] = $user->email;
    $_SESSION['USER_NAME'] = $user->nome;
    $_SESSION['USER_IS_DONATOR'] = $user->eh_doador;
    $_SESSION['USER_IS_ADMINISTRATOR'] = $user->eh_adm;
    $_SESSION['USER_IS_VOLUNTARY'] = $user->eh_voluntario;
    $_SESSION['USER_ADDRESS_ID'] = $user->id_endereco;

    return MESSAGES["USER_AUTHENTICATED"];
}
