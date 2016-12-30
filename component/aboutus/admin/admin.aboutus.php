<?php
/**
 * Created by PhpStorm.
 * User: mahdi
 * Date: 12/22/2016
 * Time: 11:30 PM
 */

include_once(dirname(__FILE__). "/model/admin.aboutus.controller.php");

global $admin_info,$PARAM;


$aboutusController = new adminAboutusController();
if(isset($exportType))
{
    $aboutusController->exportType=$exportType;
}


switch ($_GET['action'])
{

    case 'deleteAboutus':

        $input['Aboutus_id']=$_GET['id'];
        $aboutusController->deleteAboutus($input);

        break;
    case 'addAboutus':
        if(isset($_POST['action']) & $_POST['action']=='add')
        {

            $aboutusController->addAboutus($_POST);
        }
        else
        {
            $aboutusController->showAboutusAddForm('','');
        }
        break;
    case 'editAboutus':
        if(isset($_POST['action']) & $_POST['action']=='edit')
        {

            $aboutusController->editAboutus($_POST);
        }
        else
        {
            $input['Aboutus_id']=$_GET['id'];
            $aboutusController->showAboutusEditForm($input, '');
        }
        break;
    default:

        //$fields['order']['Aboutus_id'] = 'DESC';
        $aboutusController->showList($fields);
        break;
}

?>