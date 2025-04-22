<?php
include (ROOT . '/php/handlers/error_handler.php');

function routeToComonn($pageNum)
{
    switch ($pageNum) {
        case 1:
            include 'views/common/home.php';
            break;
        case 2:
            include 'views/common/login.php';
            break;
        case 3:
            include 'php/auth_services/logoff.php';
            break;
        case 4:
            showError($_GET['error']);
            include 'views/common/home.php';
            break;
        default:
            header('location: index.php?common=4&error=2');
    }
}

function routeToVoluntary($pageNum)
{
    switch ($pageNum) {
        case 1:
            include 'views/voluntary/cancelParticipation.php';
            break;
        case 2:
            include 'views/voluntary/chooseEvent.php';
            break;
        case 3:
            include 'views/voluntary/registerParticipation.php';
            break;
        default:
            header('location: index.php?common=4&error=2');
    }
}

function routeToAdministrator($pageNum)
{
    if (!$_SESSION['USER_IS_ADMINISTRATOR']) {
        header('location: index.php?common=4&error=13');
    }
    switch ($pageNum) {
        case 1:
            include 'views/administrator/createCampaign.php';
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
            include 'views/administrator/viewDonations.php';
            break;
        case 8:
            include 'views/administrator/viewCampaign.php';
            break;
        case 9:
            include 'views/administrator/viewAllDonation.php';
            break;
        case 10:
            include 'views/administrator/viewCampaignDonation.php';
            break;
        case 11:
            include 'views/administrator/viewStock.php';
            break;
        default:
            header('location: index.php?common=4&error=2');
    }
}

function routeToDonator($pageNum)
{
    switch ($pageNum) {
        case 1:
            break;
        default:
            header('location: index.php?common=4&error=2');
    }
}
