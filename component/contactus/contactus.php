<?php
/**
 * Created by PhpStorm.
 * User: marjani
 * Date: 2/27/2016
 * Time: 9:21 AM
 */
include_once(dirname(__FILE__). "/model/contactus.controller.php");

global $admin_info,$PARAM;

$contactusController = new contactusController();
if(isset($exportType))
{
    $contactusController->exportType=$exportType;
}


if(isset($_POST['action']) & $_POST['action']=='send')
{
    $contactusController->addContactus($_POST);
}
else
{
    $contactusController->showContactusForm();
}
die();



?>