<?php
/**
 * Created by PhpStorm.
 * User: marjani
 * Date: 3/06/2016
 * Time: 12:08 AM
 */
include_once(dirname(__FILE__). "/model/admin.team.controller.php");

global $admin_info,$PARAM;

$teamController = new adminTeamController();
if(isset($exportType))
{
    $teamController->exportType=$exportType;
}


switch ($_GET['action'])
{
    /*case 'showMore':
        $teamController->showMore($_GET['id']);
        break;

*/
    case 'deleteTeam':

        $input['Team_id']=$_GET['id'];
        $teamController->deleteTeam($input);

        break;
    case 'addTeam':
        if(isset($_POST['action']) & $_POST['action']=='add')
        {

            $teamController->addTeam($_POST);
        }
        else
        {
            $teamController->showTeamAddForm('','');
        }
        break;
    case 'editTeam':
        if(isset($_POST['action']) & $_POST['action']=='edit')
        {

            $teamController->editTeam($_POST);
        }
        else
        {
            $input['Team_id']=$_GET['id'];
            $teamController->showTeamEditForm($input, '');
        }
        break;
    default:

        //$fields['order']['Team_id'] = 'DESC';
        $teamController->showList($fields);
        break;
}

?>