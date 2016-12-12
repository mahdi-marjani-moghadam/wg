<?php
/**
 * Created by PhpStorm.
 * User: malek
 * Date: 3/16/2016
 * Time: 3:21 AM
 */

include_once(dirname(__FILE__). "/model/product.controller.php");

global $PARAM;

$productController = new productController();
if(isset($exportType))
{
    $productController->exportType=$exportType;
}

if($_REQUEST['action'] == 'push_rate')
{
    $fields = $_POST;

    $result = $productController->pushRate($fields);
    echo json_encode($result);
    die();
}

$productController->showProductDetail($PARAM[2]);

?>







