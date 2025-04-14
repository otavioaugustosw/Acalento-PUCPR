<?php
define('ROOT', dirname(__FILE__));
require (ROOT . '/php/config/bootstrap_php.php');
include 'php/config/session.php';
if (isset($_GET['page'])) {
    if ($_GET['page'] == 1) {
        include 'views/common/login.php' ;
    } else if ($_GET['page'] == 2) {
        include("views/common/home.php") ;
    } else if ($_GET['page'] == 3) {
        include("php/auth_services/logoff.php") ;
    } else if ($_GET['page'] == 4) {
        include("views/administrator/viewEvent.php") ;
    } else {
        header('location: index.php');
    }
} else {
    include 'views/common/header.php';
}


