<?php
/**
 * Created by PhpStorm.
 * User: marjani
 * Date: 3/6/2015
 * Time: 10:35 AM.
 */
include_once ROOT_DIR.'/common/validators.php';
class adminPortfolioModel extends looeic{
    protected $TABLE_NAME= "portfolio";
    protected  $rules=array(

        'title' => 'required*عنوان وارد نشده است',
        'url' => 'required*آادرس وارد نشده است',
        'category' => 'required*دسته بندی وارد نشده است',
    );
}
