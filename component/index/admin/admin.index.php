<?php
/**
 * Created by PhpStorm.
 * User: malek
 * Date: 2/20/2016
 * Time: 4:21 AM
 */

include_once(dirname(__FILE__). "/model/admin.index.controller.php");

global $admin_info,$PARAM;

$indexController = new adminIndexController();
if(isset($exportType))
{
    $indexController->exportType=$exportType;
}


switch ($_GET['action'])
{
    case 'showMore':
        $indexController->showMore($_GET['id']);
        break;
    case 'addIndex':
        if(isset($_POST['action']) & $_POST['action']=='add')
        {
            $indexController->addIndex($_POST);
        }
        else
        {
            $indexController->showIndexAddForm('','');
        }
        break;
    case 'editIndex':
        if(isset($_POST['action']) & $_POST['action']=='edit')
        {
            $indexController->editIndex($_POST);
        }
        else
        {
            $input['Index_id']=$_GET['id'];
            $indexController->showIndexEditForm($input, '');
        }
        break;
    case 'deleteIndex':
        $input['Index_id']=$_GET['id'];
        $indexController->deleteIndex($input);
        break;
    default:
        $indexController->showList($fields);
        break;
}

?>