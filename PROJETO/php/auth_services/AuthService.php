<?php
final class AuthService
{
    private mysqli $sql;
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const MESSAGES = [
        "BLOCK_USER" => ["status" => false, "statusName" => "BLOCK_USER"],
        "AUTH_FAILURE"=> ["status" => false, "statusName" => "AUTH_FAILURE"],
        "FETCHING_DATA_ERROR"=> ["status" => false, "statusName" => "FETCHING_DATA_ERROR"],
        "INVALID_CREDENTIALS"=> ["status" => false, "statusName" => "INVALID_CREDENTIALS"],
        "PASSWORD_AUTHENTICATED"=> ["status" => true, "statusName" => "PASSWORD_AUTHENTICATED"],
        "USER_FOUND"=> ["status" => true, "statusName" => "USER_FOUND"],
        "USER_AUTHENTICATED"=> ["status" => true, "statusName" => "USER_AUTHENTICATED"],
    ];

    function __construct(mysqli $sql) {
        $this->sql = $sql;
        $_SESSION['LOGIN_ATTEMPTS'] = ($_SESSION['LOGIN_ATTEMPTS'] ?? 0);
    }

    public function authenticateUser($email, $typedPassword): array
    {
        if ($_SESSION['LOGIN_ATTEMPTS'] >= $this::MAX_LOGIN_ATTEMPTS) {
            sleep(3);
            return ["status" => false, "statusName" => "BLOCK"];
        }
        try {
            $userExists = $this->getUserByEmail($email);
            if (!$userExists["status"]) {
                $this->handleFailedAttempt();
                return $userExists;
            }
            $user = $userExists["user"];
            $passwordResult = $this->isPasswordCorrect($typedPassword, $user->senha);
            if (!$passwordResult["status"]) {
                $this->handleFailedAttempt();
                return $passwordResult;
            }
            session_regenerate_id(true);
            $_SESSION["USER_ID"] = $user->id;
            $_SESSION['LOGIN_ATTEMPTS'] = 0;
            return $this->loadUserSessionData();
        } catch (mysqli_sql_exception $E) {
            return self::MESSAGES["AUTH_FAILURE"];
    }
    }

    public static function generatePasswordHash($typedPassword): string
    {
        return password_hash($typedPassword . $_ENV['PEPPER_KEY'], PASSWORD_DEFAULT);
    }

    private function verifyQueryResult($user): array
    {
        if (!$user) {
            return self::MESSAGES["FETCHING_DATA_ERROR"];
        }
        return self::MESSAGES["USER_FOUND"];
    }

    private function handleFailedAttempt() {
        $_SESSION['LOGIN_ATTEMPTS'] = $_SESSION['LOGIN_ATTEMPTS'] + 1;
    }
    private function loadUserSessionData(): array
    {
        $query = "SELECT email, nome, eh_doador, eh_adm, eh_voluntario, id_endereco FROM usuario WHERE id = ?;";
        $stmt = $this->sql->prepare($query);
        $stmt->bind_param("i", $_SESSION['USER_ID']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_object();
        $resultStatus = $this->verifyQueryResult($user);
        if (!$resultStatus["status"]) {
            return $resultStatus;
        }
        $_SESSION['USER_EMAIL'] = $user->email;
        $_SESSION['USER_NAME'] = $user->nome;
        $_SESSION['USER_IS_DONATOR'] = $user->eh_doador;
        $_SESSION['USER_IS_ADMINISTRATOR'] = $user->eh_adm;
        $_SESSION['USER_IS_VOLUNTARY'] = $user->eh_voluntario;
        $_SESSION['USER_ADDRESS_ID'] = $user->id_endereco;

        return self::MESSAGES["USER_AUTHENTICATED"];
    }

    private function getUserByEmail($email): array
    {
        $query = "SELECT id, senha FROM usuario WHERE email = ?";
        $stmt = $this->sql->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_object();
        $resultStatus = $this->verifyQueryResult($user);
        if (!$resultStatus["status"]) {
            return $resultStatus;
        }
        return ["status"=>true,"user"=>$user];
    }

    private function isPasswordCorrect($password, $hashedPassword): array
    {
        $pepperedPassword = $password . $_ENV['PEPPER_KEY'];
        if(password_verify($pepperedPassword, $hashedPassword)){
            return self::MESSAGES["PASSWORD_AUTHENTICATED"];
        } else {
            return self::MESSAGES["INVALID_CREDENTIALS"];
        }
    }
}


