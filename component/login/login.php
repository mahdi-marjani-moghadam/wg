<?php
/**
 * Created by PhpStorm.
 * User: malek
 * Date: 2/20/2016
 * Time: 4:21 AM
 */
include_once(dirname(__FILE__). "/model/login.controller.php");

global $admin_info,$PARAM;

$loginController = new loginController();


if(isset($exportType))
{
    $loginController->exportType=$exportType;
}

$loginController->showLoginForm();
die();

?>