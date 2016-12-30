<?php
/**
 * Created by PhpStorm.
 * User: mahdi
 * Date: 12/22/2016
 * Time: 10:34 PM
 */

include_once(dirname(__FILE__). "/model/aboutus.controller.php");
global $admin_info,$PARAM;
$aboutusController = new aboutusController();
if(isset($exportType))
{
    $aboutusController->exportType=$exportType;
}
/*if(isset($PARAM[1]))
{
    $aboutusController->showMore($PARAM[1]);
    die();
}else
{*/
    //$fields['filter']['title']='sdf';
    $fields['limit']['start']=(isset($page))?($page-1)*PAGE_SIZE:'0';
    $fields['limit']['length']=PAGE_SIZE;
    $fields['order']['Aboutus_id']='DESC';
    $aboutusController->showALL($fields);
    die();
//}