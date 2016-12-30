<?php
/**
 * Created by PhpStorm.
 * User: malek,marjani
 * Date: 2/21/2016
 * Time: 4:21 AM
 */
include_once(dirname(__FILE__). "/model/notFound.controller.php");

global $admin_info,$PARAM;

$controller = new notFoundController();
if(isset($exportType))
{
    $controller->exportType=$exportType;
}
$controller->show($PARAM[1]);
?>