<?php
define('ROOT', dirname(__FILE__));
require (ROOT . '/php/config/bootstrap_php.php');
include 'php/config/session.php';
include (ROOT . '/php/handlers/router.php');

if (isset($_GET['common'])) {
    routeToComonn($_GET['common']);
} else if (isset($_GET['adm'])) {
    routeToAdministrator($_GET['adm']);
} else if (isset($_GET['voluntary'])) {
    routeToVoluntary($_GET['voluntary']);
} else if (isset($_GET['donator'])) {
    routeToDonator($_GET['donator']);
} else {
    routeToComonn(4);
}
