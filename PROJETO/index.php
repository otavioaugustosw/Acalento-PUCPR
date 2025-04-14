<?php
define('ROOT', dirname(__FILE__));
require (ROOT . '/php/config/bootstrap_php.php');
include 'php/config/session.php';

const PAGE_ERROR = [
    1=> "Não possui acesso administrador",
    2=> "Página não existente",
];

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

function routeToComonn($pageNum)
{
    switch ($pageNum) {
        case 1:
            showError($_GET['error']);
            include 'views/common/home.php';
            break;
        case 2:
            include 'views/common/login.php';
            break;
        case 3:
            include 'php/auth_services/logoff.php';
            break;
        case 4:
            include 'views/common/home.php';
            break;
        default:
            header('location: index.php?common=1&error=2');
    }
}

function routeToVoluntary($pageNum)
{
    switch ($pageNum) {
        case 1:
            include 'views/common/login.php';
            break;
        case 2:
            include 'views/common/login.php';
            break;
        case 3:
            include 'views/common/login.php';
            break;
        default:
            header('location: index.php?common=1&error=2');
    }
}

function routeToAdministrator($pageNum)
{
    if (!$_SESSION['USER_IS_ADMINISTRATOR']) {
        header('location: index.php?common=1&error=13');
    }
    switch ($pageNum) {
        case 1:
            include 'views/administrator/createDonation.php';
            break;
        case 2:
            include 'views/administrator/createEvent.php';
            break;
        case 3:
            include 'views/administrator/createItem.php';
            break;
        case 4:
            include 'views/administrator/deleteEvent.php';
            break;
        case 5:
            include 'views/administrator/editEvent.php';
            break;
        case 6:
            include 'views/administrator/updateEvent.php';
            break;
        case 7:
            include 'views/administrator/viewAllDonation.php';
            break;
        case 8:
            include 'views/administrator/viewCampaign.php';
            break;
        case 9:
            include 'views/administrator/viewDonation.php';
            break;
        case 10:
            include 'views/administrator/viewEvent.php';
            break;
        default:
            header('location: index.php?common=1&error=2');
    }
}

function routeToDonator($pageNum)
{
    switch ($pageNum) {
        case 1:
            include 'views/common/login.php';
            break;
        case 2:
            include 'views/common/login.php';
            break;
        case 3:
            include 'views/common/login.php';
            break;
        default:
            header('location: index.php?common=1&error=2');
    }
}

function showError($pageNum)
{
        ?>
            <div class="toast-container position-absolute p-3 top-0 start-50 translate-middle-x">
                <div class="toast text-bg-danger border-0 show" id="toastRuim">
                    <div class="d-flex">
                        <div class="toast-body">
                            <?= isset(PAGE_ERROR[$pageNum]) ? PAGE_ERROR[$pageNum] : "Acão inexistente" ?>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fechar"></button>
                    </div>
                </div>
            </div>
    <?php
}
