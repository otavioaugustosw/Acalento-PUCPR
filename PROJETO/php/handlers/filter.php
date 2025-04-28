<?php
include (ROOT . '/php/handlers/hasColumn.php');

function setWhere(string $nome): string
{
    $db    = connectDatabase();
    $table = $nome; // ex.: "evento"   ou  "item"

    if($table === 'item' || $table === 'campanha_doacao') {
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

    if (hasColumn($db,$table,'status')) {

        if ($dia !== '') {
            // dia exato tem prioridade
            $where = "WHERE DATE($table.data) = '$dia' AND $table.status = 0";
        } else {
            switch ($filtro) {
                case 'futuros':
                    $where = "WHERE $table.data >= NOW() AND $table.status = 0";
                    break;

                case 'passados':
                    $where = "WHERE $table.data <= NOW() AND $table.status = 0";
                    break;
                case "mes":
                    /* DATESUB: para subtrair um mês do dia de hoje (CURRENT_DATE)
                    DATE_FORMAT: pega a data e formata ela, nessa caso formata para o primeiro dia do mês
                    LAST_DAY: pega o último dia do mês */
                    $where = "WHERE $table.data BETWEEN
                    DATE_FORMAT(DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH), '%Y-%m-01') AND LAST_DAY(DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH))
                    AND $table.status = 0";
                    break;
                case 'todos':
                    $where = "WHERE $table.status = 0";
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