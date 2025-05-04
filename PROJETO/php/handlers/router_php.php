<?php
include(ROOT . '/php/handlers/error_handler_php.php');

function routeToCommon($pageNum)
{
    switch ($pageNum) {
        case 1:
            include 'views/common/public_home.php';
            break;
        case 2:
            include 'views/common/login.php';
            break;
        case 3:
            include 'php/auth_services/logoff_php.php';
            break;
        case 4:
            showError($_GET['error']);
            include 'views/common/logged_home.php';
            break;
        case 5:
            include 'views/common/register_user.php';
            break;
        case 6:
            include 'views/common/logged_home.php';
            break;
        case 7:
            include 'views/common/view_user.php';
            break;
        case 8:
            include 'views/common/edit_user.php';
            break;
        case 9:
            include 'views/common/delete_user_php.php';
            break;
        case 10:
            include 'views/common/edit_password.php';
            break;
        case 11:
            include 'views/common/public_home.php';
            break;

        default:
            header('location: index.php?common=4&error=2');
    }
}

function routeToVoluntary($pageNum)
{
    switch ($pageNum) {
        case 1:
            include 'views/voluntary/cancel_participation_php.php';
            break;
        case 2:
            include 'views/voluntary/choose_event.php';
            break;
        case 3:
            include 'views/voluntary/register_participation_php.php';
            break;
        case 4:
            showError($_GET['error']);
            include 'views/voluntary/choose_event.php';
            break;
        case 5:
            showSucess($_GET['success']);
            include 'views/voluntary/choose_event.php';
            break;
        default:
            header('location: index.php?common=4&error=2');
    }
}

function routeToAdministrator($pageNum)
{
    if (!$_SESSION['USER_IS_ADMINISTRATOR']) {
        header('location: index.php?common=4&error=1');
    }
    switch ($pageNum) {
        case 1:
            include 'views/administrator/create_campaign.php';
            break;
        case 2:
            include 'views/administrator/create_event.php';
            break;
        case 3:
            include 'views/administrator/register_donation.php';
            break;
        case 4:
            include 'views/administrator/delete_event_php.php';
            break;
        case 5:
            include 'views/administrator/edit_event.php';
            break;
        case 6:
            include 'views/administrator/update_event.php';
            break;
        case 7:
            include 'views/administrator/donations_hub.php';
            break;
        case 8:
            include 'views/administrator/view_campaign.php';
            break;
        case 9:
            header('location: index.php?adm=15&view=adm');
            break;
        case 10:
            header('location: index.php?adm=15&view=campaign&id='. $_GET['id']);
            break;
        case 11:
            header('location: index.php?adm=15&view=inventory');
            break;
        case 12:
            include 'views/administrator/register_admin.php';
            break;
        case 13:
            showError($_GET['error']);
            include 'views/administrator/edit_event.php';
            break;
        case 14:
            showSucess($_GET['success']);
            include 'views/administrator/edit_event.php';
            break;
        case 15:
            include 'views/donator/view_donations.php';
            break;
        default:
            header('location: index.php?common=4&error=2');
    }
}

function routeToDonator($pageNum)
{
    switch ($pageNum) {
        case 1:
            include 'views/donator/view_donations.php';
            break;
        default:
            header('location: index.php?common=4&error=2');
    }
}
