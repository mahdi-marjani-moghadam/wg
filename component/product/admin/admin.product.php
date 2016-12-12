<?php

/**
 * Created by PhpStorm.
 * User: malekloo
 * Date: 3/28/2016
 * Time: 9:21 AM
 */
include_once(dirname(__FILE__). "/model/admin.product.controller.php");

global $admin_info,$PARAM;

$productController = new adminProductController();
if(isset($exportType))
{
    $productController->exportType=$exportType;
}

switch ($_GET['action'])
{
    case 'showMore':
        $productController->showMore($_GET['id']);
        break;
    case 'add':

        if(isset($_POST['action']) & $_POST['action']=='add')
        {

            $productController->addProduct($_POST);
        }
        else
        {
            $fields['company_id']=$_GET['company_id'];
            $productController->showProductAddForm($fields,'');
        }
        break;
    case 'edit':
        if(isset($_POST['action']) & $_POST['action']=='edit')
        {

            $productController->editProduct($_POST);
        }
        else
        {
            $input['Artists_products_id']=$_GET['id'];
            $productController->showProductEditForm($input,'');
        }
        break;
    case 'deleteProduct':
        $productController->deleteProduct($_GET['id']);

        break;
    default:

        $fields['choose']['artists_id']=$_GET['id'];
        $productController->showList($fields);
        break;
}

?>