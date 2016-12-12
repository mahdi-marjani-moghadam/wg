<?php

/**
 * Created by PhpStorm.
 * User: malekloo
 * Date: 2/27/2016
 * Time: 9:21 AM.
 */
include_once dirname(__FILE__).'/model/admin.artists.controller.php';

global $admin_info,$PARAM;
$artistsController = new adminArtistsController();
if (isset($exportType)) {
    $artistsController->exportType = $exportType;
}

switch ($_GET['action']) {
    case 'expired':
        $artistsController->showExpiredList();
        break;
    case 'unverified':
        $artistsController->showUnverifiedList();
        break;
    case 'add':

        if (isset($_POST['action']) & $_POST['action'] == 'add') {
            $artistsController->addArtists($_POST);
        } else {
            $artistsController->showArtistsAddForm('', '');
        }
        break;
    case 'edit':
        if (isset($_POST['action']) & $_POST['action'] == 'edit') {
            $artistsController->editArtists($_POST);
        } else {
            $input['Artists_id'] = $_GET['id'];
            $input['showStatus'] = $_GET['showStatus'];
            $artistsController->showArtistsEditForm($input, '');
        }
        break;
    case 'delete':
        $artistsController->deleteArtists($_GET['id']);
        break;
    /*case 'call':
        $artistsController->call($_POST);
        break;
    case 'importCompanies':
        $artistsController->importCompanies();
        break;*/
    case 'updateCity':
        $artistsController->updateCity();
        break;    

    case 'search':
        $artistsController->search($_GET);
        break;
    case 'searchExpire':
        $artistsController->searchExpire($_GET);
        break;
    case 'getArtistsPhone':

        $artistsController->getArtistsphone($_POST);
        break;
    case 'searchUnverified':
        $artistsController->searchUnverified($_GET);
        break;
    case 'getCityAjax':
        $artistsController->getCityAjax($_POST);
        break;
    default:
        $artistsController->showList($msg);
        break;
}
