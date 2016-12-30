<?php
/**
 * Created by PhpStorm.
 * User: marjani
 * Date: 3/10/2016
 * Time: 10:21 AM
 */

include_once(dirname(__FILE__). "/model/team.controller.php");

global $admin_info,$PARAM;

$teamController = new teamController();
if(isset($exportType))
{
    $teamController->exportType=$exportType;
}

if(isset($PARAM[1]))
{
    $teamController->showMore($PARAM[1]);
    die();
}else
{

    //$fields['filter']['title']='sdf';

    $fields['limit']['start']=(isset($page))?($page-1)*PAGE_SIZE:'0';
    $fields['limit']['length']=PAGE_SIZE;
    $fields['order']['Team_id']='DESC';
    $teamController->showALL($fields);
    die();
}


?>