<?php
include_once (ROOT . '/php/handlers/error_handler_php.php');

function routeToCommon($pageNum)
{
    switch ($pageNum) {
        case 1:
            include_once 'views/common/public_home.php';
            break;
        case 2:
            include_once 'views/common/login.php';
            break;
        case 3:
            include_once 'php/auth_services/logoff_php.php';
            break;
        case 4:
            showError($_GET['error']);
            include_once 'views/common/logged_home.php';
            break;
        case 5:
            include_once 'views/common/register_user.php';
            break;
        case 6:
            include_once 'views/common/logged_home.php';
            break;
        case 7:
            include_once 'views/common/view_user.php';
            break;
        case 8:
            include_once 'views/common/edit_user.php';
            break;
        case 9:
            include_once 'views/common/delete_user_php.php';
            break;
        case 10:
            include_once 'views/common/edit_password.php';
            break;
        default:
            header('location: index.php?common=4&error=2');
    }
}

function routeToVoluntary($pageNum)
{
    switch ($pageNum) {
        case 1:
            include_once 'views/voluntary/cancel_participation_php.php';
            break;
        case 2:
            include_once 'views/voluntary/choose_event.php';
            break;
        case 3:
            include_once 'views/voluntary/register_participation_php.php';
            break;
        case 4:
            include_once 'views/voluntary/event_detail.php';
            break;
        case 5:
            include_once 'views/voluntary/confirm_participation_php.php';
            break;
        case 6:
            include_once 'views/voluntary/confirm_events.php';
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
            include_once 'views/administrator/create_campaign.php';
            break;
        case 2:
            include_once 'views/administrator/create_event.php';
            break;
        case 3:
            include_once 'views/administrator/register_donation.php';
            break;
        case 4:
            include_once 'views/administrator/delete_event_php.php';
            break;
        case 5:
            include_once 'views/administrator/manage_events.php';
            break;
        case 6:
            include_once 'views/administrator/edit_event.php';
            break;
        case 7:
            include_once 'views/administrator/donations_hub.php';
            break;
        case 8:
            include_once 'views/administrator/view_campaign.php';
            break;
        case 9:
            include_once 'views/administrator/manage_punishments.php';
            break;
        case 10:
            include_once 'views/administrator/edit_punishments.php';
            break;
        case 11:
            include_once 'views/administrator/register_admin.php';
            break;
        case 12:
            include_once 'views/administrator/manage_check_in.php';
            break;
        case 13:
            include_once 'views/administrator/event_check_in.php';
            break;
        case 14:
            break;
        case 15:
            include_once 'views/donator/view_donations.php';
            break;
        default:
            header('location: index.php?common=4&error=2');
    }
}

function routeToDonator($pageNum)
{
    switch ($pageNum) {
        case 1:
            include_once 'views/donator/view_donations.php';
            break;
        default:
            header('location: index.php?common=4&error=2');
    }
}
