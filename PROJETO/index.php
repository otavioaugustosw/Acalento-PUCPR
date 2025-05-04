<?php
define('ROOT', dirname(__FILE__));
require (ROOT . '/php/config/bootstrap_php.php');
include (ROOT . '/php/config/session_php.php');
include (ROOT . '/php/handlers/router_php.php');
include (ROOT . '/php/handlers/formater_php.php');

if (isset($_GET['common'])) {
    routeToCommon($_GET['common']);
} else if (isset($_GET['adm'])) {
    routeToAdministrator($_GET['adm']);
} else if (isset($_GET['voluntary'])) {
    routeToVoluntary($_GET['voluntary']);
} else if (isset($_GET['donator'])) {
    routeToDonator($_GET['donator']);
} else {
    routeToCommon(1);
}
