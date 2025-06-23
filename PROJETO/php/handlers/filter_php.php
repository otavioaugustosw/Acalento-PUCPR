<?php
/**
 * @param mysqli $bd recebe a conexão com o banco de dados
 * @param string $tabela recebe o nome da tabela
 * @param string $coluna recebe a coluna quer você, quer saber se existe ou não
 * @return bool
 */
function hasColumn(mysqli $bd, string $tabela, string $coluna): bool
{
    $query = "SHOW COLUMNS FROM $tabela LIKE '$coluna'";
    $resultado = $bd->query($query);
    if (!$resultado) {
        return false;
    }
    $existe = $resultado->num_rows > 0;

    $resultado->free();
    return $existe;
}

function set_where_events(string $nome)
{
    $db    = connectDatabase();
    $table = $nome; // ex.: "evento"   ou  "item"

    $dia    = $_POST['dia']    ?? '';

    $filtro = $_POST['filtro'] ?? 'futuros';

    if (($filtro === '' || $filtro === null) && ($dia === '' || $dia === null)) {
        $filtro = 'todos';
    }

    $opcoesValidas = ['futuros','passados','todos', 'mes'];
    if (!in_array($filtro,$opcoesValidas)) $filtro = 'futuros';

    if ($dia!=='' && !preg_match('/^\d{4}-\d{2}-\d{2}$/',$dia)) $dia='';

    /* monta o WHERE ------------------------------------------------ */
    $where = '';

    if ($dia !== '') {
        // dia exato tem prioridade
        $where = "WHERE DATE($table.data) = '$dia' AND $table.inativo = 0";
    } else {
        switch ($filtro) {
            case 'futuros':
                $where = "WHERE $table.data >= NOW() AND $table.inativo = 0";
                break;

            case 'passados':
                $where = "WHERE $table.data <= NOW() AND $table.inativo = 0";
                break;
            case "mes":
                /* DATESUB: para subtrair um mês do dia de hoje (CURRENT_DATE)
                DATE_FORMAT: pega a data e formata ela, nessa caso formata para o primeiro dia do mês
                LAST_DAY: pega o último dia do mês */
                $where = "WHERE $table.data BETWEEN
                    DATE_FORMAT(DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH), '%Y-%m-01') AND LAST_DAY(DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH))
                    AND $table.inativo = 0";
                break;
            case 'todos':
                $where = "WHERE $table.inativo = 0";
                break;
        }
    }
    return $where;
}


function setWhere(string $nome): string
{
    $db    = connectDatabase();
    $table = $nome; // ex.: "evento"   ou  "item"

    if($table === 'doacao' || $table === 'doacao_monetaria') {
        $filtro = $_POST['filtro'] ?? 'todos';
    } else {
        $filtro = $_POST['filtro'] ?? 'futuros';
    }
    $dia    = $_POST['dia']    ?? '';

    if (($filtro === '' || $filtro === null) && ($dia === '' || $dia === null)) {
        $filtro = 'todos';
    }

    /* sanitização… ------------------------------------------------- */
    $opcoesValidas = ['futuros','passados','todos', 'mes'];
    if (!in_array($filtro,$opcoesValidas)) $filtro = 'futuros';

    if ($dia!=='' && !preg_match('/^\d{4}-\d{2}-\d{2}$/',$dia)) $dia='';

    /* monta o WHERE ------------------------------------------------ */
    $where = '';

    if (hasColumn($db,$table,'inativo')) {

        if ($dia !== '') {
            // dia exato tem prioridade
            $where = "WHERE DATE($table.data) = '$dia' AND $table.inativo = 0";
        } else {
            switch ($filtro) {
                case 'futuros':
                    $where = "WHERE $table.data >= NOW() AND $table.inativo = 0";
                    break;

                case 'passados':
                    $where = "WHERE $table.data <= NOW() AND $table.inativo = 0";
                    break;
                case "mes":
                    /* DATESUB: para subtrair um mês do dia de hoje (CURRENT_DATE)
                    DATE_FORMAT: pega a data e formata ela, nessa caso formata para o primeiro dia do mês
                    LAST_DAY: pega o último dia do mês */
                    $where = "WHERE $table.data BETWEEN
                    DATE_FORMAT(DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH), '%Y-%m-01') AND LAST_DAY(DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH))
                    AND $table.inativo = 0";
                    break;
                case 'todos':
                    $where = "WHERE $table.inativo = 0";
                    break;
            }
        }

    } else {
        if ($dia !== '') {
            $where = "WHERE DATE($table.data) = '$dia'";
        } else {
            switch ($filtro) {
                case 'futuros':
                    $where = "WHERE $table.data >= NOW()";
                    break;
                case 'passados':
                    $where = "WHERE $table.data <= NOW()";
                    break;
                case "mes":
                    $where = "WHERE $table.data BETWEEN 
                    DATE_FORMAT(DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH), '%Y-%m-01') AND 
                    LAST_DAY(DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH))";
                    break;
                case 'todos':
                    $where = "WHERE 1=1";           // sem filtro adicional
                    break;
            }
        }
    }

    return $where;
}

function set_where_donation($view, $filtro = 'todos'): array
{
    $usuario_id = $_SESSION['USER_ID'];

    $opcoes_validas = ['material', 'monetario', 'todos'];
    if (!in_array($filtro, $opcoes_validas)) {
        $filtro = 'todos';
    }

    $base_material = setWhere('doacao');
    $base_monetario = setWhere('doacao_monetaria');

    if ($view !== 'adm') {
        $base_material .= " AND doacao.id_usuario = $usuario_id";
        $base_monetario .= " AND doacao_monetaria.id_usuario = $usuario_id";
    }

    switch ($filtro) {
        case 'material':
            return [
                'where_material' => $base_material,
                'where_monetario' => 'WHERE 1=0'
            ];
        case 'monetario':
            return [
                'where_material' => 'WHERE 1=0',
                'where_monetario' => $base_monetario
            ];
        case 'todos':
        default:
            return [
                'where_material' => $base_material,
                'where_monetario' => $base_monetario
            ];
    }
}

function set_where_my_events(){

    $filtro = $_POST['filtro'] ?? 'todos';

    /* satinização */
    $opcoes_valida = ['ha_confirmar', 'confirmado', 'presente', 'todos'];
    if (!in_array($filtro,$opcoes_valida)) {
        $filtro = 'todos';
    }

    $where = '';
    $usuario = 'id_usuario = ' . $_SESSION['USER_ID'];

    switch ($filtro) {
        case 'ha_confirmar':
            $where = 'WHERE participacao_confirmada = 0 AND evento.inativo = 0 AND ' . $usuario;
            break;
        case 'confirmado':
            $where = 'WHERE participacao_confirmada = 1 AND presenca = 0 AND evento.inativo = 0 AND ' . $usuario;
            break;
        case 'presente':
            $where = 'WHERE presenca = 1 AND evento.inativo = 0 AND ' . $usuario;
            break;
        case 'todos':
            $where = 'WHERE evento.inativo = 0 AND ' . $usuario;
            break;
    }
    return $where;
}

function set_where_user() {
    $filtro = $_POST['filtro'] ?? 'todos';

    /* sanitização */
    $opcoes_valida = ['voluntario', 'doador', 'administrador', 'todos'];
    if (!in_array($filtro,$opcoes_valida)) {
        $filtro = 'todos';
    }

    $where = '';

    switch ($filtro) {
        case 'voluntario':
            $where = 'WHERE eh_voluntario = 1';
            break;
        case 'doador':
            $where = 'WHERE eh_doador = 1';
            break;
        case 'administrador':
            $where = 'WHERE eh_adm = 1';
            break;
        case 'todos':
            $where = 'WHERE 1=1';
            break;
    }

    return $where;
}

function set_where_validate()
{
    $filtro = $_POST['filtro'] ?? 'aprovar';

    /* sanitização */
    $opcoes_valida = ['aprovar', 'nao_aprovados', 'aprovados', 'todos'];
    if (!in_array($filtro,$opcoes_valida)) {
        $filtro = 'aprovar';
    }

    $where = '';

    switch ($filtro) {
        case 'aprovar':
            $where = 'WHERE validado = 0';
            break;
        case 'nao_aprovados':
            $where = 'WHERE validado = 2';
            break;
        case 'aprovados':
            $where = 'WHERE validado = 1';
            break;
        case 'todos':
            $where = 'WHERE 1=1';
            break;
    }

    return $where;
}

function set_where_punicao($admin = false): string
{
    $filtro = $_POST['filtro'] ?? 'todos';
    $dia    = $_POST['dia']    ?? '';

    $opcoesValidas = ['futuros','passados','todos', 'mes'];
    if (!in_array($filtro, $opcoesValidas)) $filtro = 'futuros';
    if ($dia !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dia)) $dia = '';

    if ($dia !== '') {
        return "WHERE DATE(usuario_punicao.data_punicao) = '$dia'";
    }

    $user = '';
    if ($admin) {
        $user = "usuario_punicao.id_usuario != " . $_SESSION['USER_ID'];
    } else {
        $user = "usuario_punicao.id_usuario = " . $_SESSION['USER_ID'];
    }

    switch ($filtro) {
        case 'futuros':
            return "WHERE usuario_punicao.data_punicao >= NOW() AND $user";
        case 'passados':
            return "WHERE usuario_punicao.data_punicao <= NOW() AND $user";
        case 'mes':
            return "WHERE usuario_punicao.data_punicao BETWEEN
                DATE_FORMAT(DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH), '%Y-%m-01') AND 
                LAST_DAY(DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)) AND $user";
        case 'todos':
        default:
            return "WHERE $user";
    }
}