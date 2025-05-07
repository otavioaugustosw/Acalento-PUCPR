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


function setWhere(string $nome): string
{
    $db    = connectDatabase();
    $table = $nome; // ex.: "evento"   ou  "item"

    if($table === 'doacao' || $table === 'campanha_doacao') {
        $filtro = $_POST['filtro'] ?? 'todos';
    } else {
        $filtro = $_POST['filtro'] ?? 'futuros';
    }
    $dia    = $_POST['dia']    ?? '';

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

function set_where_donations($view, $campaign_id = 0)
{
    if ($_SESSION['USER_IS_ADMINISTRATOR'] && isset($view)) {
        switch ($view) {
            case 'adm':
                return setWhere('doacao');
            case 'inventory':
               return setWhere('doacao') . " AND doacao.id_estoque IS NOT NULL";
            case 'campaign':
                if ($campaign_id <= 0) {
                    showError(10);
                    return setWhere('doacao') . " AND id_usuario =" . $_SESSION['USER_ID'];
                }
                return setWhere('doacao') . " AND doacao.id_campanha_doacao = $campaign_id";
            default:
                return setWhere('doacao') . " AND id_usuario =" . $_SESSION['USER_ID'];
        }
    } else {
        return setWhere('doacao') . " AND id_usuario =" . $_SESSION['USER_ID'];
    }
}