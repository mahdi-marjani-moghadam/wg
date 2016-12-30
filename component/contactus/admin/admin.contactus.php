<?php
/**
 * Created by PhpStorm.
 * User: marjani
 * Date: 2/27/2016
 * Time: 11:40 AM
 */
include_once(dirname(__FILE__). "/model/admin.contactus.controller.php");

global $admin_info,$PARAM;

$contactusController = new adminContactusController();
if(isset($exportType))
{
    $contactusController->exportType=$exportType;
}


switch ($_GET['action'])
{
    case 'showMore':
        $contactusController->showMore($_GET['id']);
        break;
    case 'addContactus':


        if(isset($_POST['action']) & $_POST['action']=='add')
        {

            $contactusController->addContactus($_POST);
        }
        else
        {
            $contactusController->showContactusAddForm('','');
        }
        break;
    case 'editContactus':


        if(isset($_POST['action']) & $_POST['action']=='edit')
        {

            $contactusController->editContactus($_POST);
        }
        else
        {
            $input['Contactus_id']=$_GET['id'];
            $contactusController->showContactusEditForm($input, '');
        }
        break;
    case 'deleteContactus':

        $input['Contact_id']=$_GET['id'];
        $contactusController->deleteContactus($input);

        break;
    default:

        $fields['limit']['start']=(isset($_GET['page']))?($_GET['page']-1)*PAGE_SIZE:'0';
        $fields['limit']['length']=PAGE_SIZE;
        $fields['order']['Contact_id']='DESC';
        $contactusController->showList($fields);
        break;
}

?>