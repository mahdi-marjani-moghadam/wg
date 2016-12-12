<?php
/**
 * Created by PhpStorm.
 * User: marjani
 * Date: 2/28/2016
 * Time: 3:21 AM.
 */
include_once dirname(__FILE__).'/model/index.controller.php';

global $admin_info,$PARAM;

$indexController = new indexController();
if (isset($exportType)) {
    $indexController->exportType = $exportType;
}

$indexController->showALL($fields);

?>
