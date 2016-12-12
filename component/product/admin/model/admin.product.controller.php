<?php
/**
 * Created by PhpStorm.
 * User: malekloo
 * Date: 3/6/2016
 * Time: 11:21 AM
 */

include_once(dirname(__FILE__)."/admin.product.model.php");

/**
 * Class registerController
 */
class adminProductController
{

    /**
     * Contains file type
     * @var
     */
    public $exportType;

    /**
     * Contains file name
     * @var
     */
    public $fileName;

    /**
     * registerController constructor.
     */
    public function __construct()
    {
        $this->exportType='html';

    }

    /**
     * call template
     *
     * @param string $list
     * @param $msg
     * @return string
     */
    function template($list=array(), $msg)
    {
        global $messageStack;

        switch($this->exportType)
        {
            case 'html':
                include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/template_start.php");
                include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/template_header.php");
                include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/template_rightMenu_admin.php");
                include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/$this->fileName");
                include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/template_footer.php");
                include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/template_end.php");
                break;

            case 'json':
                echo json_encode($list);
                break;

            case 'array':
                return $list;
                break;

            case 'serialize':
                 echo serialize($list);
                break;

            default:
                break;
        }

    }

    /**
     * add Product
     *
     * @param $_input
     * @return int|mixed
     * @author marjani
     * @date 2/27/2015
     * @version 01.01.01
     */
    public function addProduct($_input)
    {
        global $messageStack;


        $product=new adminProductModel;

        $_input['category_id'] = ",".(implode(",",$_input['category_id'])).",";
        $fields['artists_id'] = $_input['artists_id'] = $_REQUEST['artists_id'];
        $result=$product->setFields($_input);



        if($result['result']==-1)
        {
            $this->showProductAddForm($_input,$result['msg']);
        }
        $result=$product->save();

        if(file_exists($_FILES['file']['tmp_name'])){

            $type  = explode('/',$_FILES['file']['type']);
            $input['max_size'] = $_FILES['file']['size'];
            $input['upload_dir'] = ROOT_DIR.'statics/files/'.$fields['artists_id'].'/';
            $result = fileUploader($input,$_FILES['file']);

            fileRemover($input['upload_dir'],$product->fields['file']);

            $product->file_type = $type[0];
            $product->extension = $type[1];
            $product->file = $result['image_name'];
            $result = $product->save();
        }

        if(file_exists($_FILES['image']['tmp_name'])){

            $type  = explode('/',$_FILES['image']['type']);

            $input['upload_dir'] = ROOT_DIR.'statics/files/'.$fields['artists_id'].'/';
            $result = fileUploader($input,$_FILES['image']);
            fileRemover($input['upload_dir'],$product->fields['image']);
            $product->image = $result['image_name'];
            $result = $product->save();
        }

        //$result=$product->addProduct();

        if($result['result']!='1')
        {
            $messageStack->add_session('register',$result['msg']);
            $this->showProductAddForm($_input,$result['msg']);
        }
        $msg='عملیات با موفقیت انجام شد';
        $messageStack->add_session('register',$msg);

        redirectPage(RELA_DIR . "admin/?component=product&id={$_input['artists_id']}", $msg);
        die();


    }


    /**
     * call register form
     *
     * @param $fields
     * @param $msg
     * @return mixed
     * @author malekloo
     * @date 14/03/2016
     * @version 01.01.01
     */

    public function showProductAddForm($fields,$msg)
    {

        include_once(ROOT_DIR."component/category/admin/model/admin.category.model.php");
        $category = new adminCategoryModel();

        $resultCategory = $category->getCategoryOption();

        if($resultCategory['result'] == 1)
        {
            $fields['category'] = $category->list;
        }


        $this->fileName='admin.product.addForm.php';
        $this->template($fields,$msg);
        die();
    }


    /**
     * @param $fields
     * @return mixed
     * @author malekloo
     * @date 3/16/2015
     * @version 01.01.01
     */
    public function editProduct($fields)
    {
        //$product=new adminProductModel();

        //$result    = $product->getProductById($fields['Artists_products_id']);
        $product = adminProductModel::find($fields['Artists_products_id']);

        if(!is_object($product))
        {
            redirectPage(RELA_DIR . "admin/index.php?component=product", $product['msg']);
        }

        $product->setFields($fields);


        $product->category_id = ",".(implode(",",$product->category_id)).",";




        $result=$product->save();
        $fields=$product->fields;
        if($result['result']!='1')
        {
            $this->showProductEditForm($fields,$result['msg']);
        }


        if(file_exists($_FILES['file']['tmp_name'])){

            $type  = explode('/',$_FILES['file']['type']);
            $input['max_size'] = $_FILES['file']['size'];
            $input['upload_dir'] = ROOT_DIR.'statics/files/'.$fields['artists_id'].'/';
            $result = fileUploader($input,$_FILES['file']);

            fileRemover($input['upload_dir'],$product->fields['file']);

            $product->file_type = $type[0];
            $product->extension = $type[1];
            $product->file = $result['image_name'];
            $result = $product->save();
        }

        if(file_exists($_FILES['image']['tmp_name'])){

            $type  = explode('/',$_FILES['image']['type']);

            $input['upload_dir'] = ROOT_DIR.'statics/files/'.$fields['artists_id'].'/';
            $result = fileUploader($input,$_FILES['image']);
            fileRemover($input['upload_dir'],$product->fields['image']);
            $product->image = $result['image_name'];

            $result = $product->save();
        }

        $msg='عملیات با موفقیت انجام شد';
        redirectPage(RELA_DIR . "admin/index.php?component=product&id={$fields['artists_id']}", $msg);
        die();
    }


    /**
     * @param $fields
     * @return mixed
     * @author malekloo
     * @date 3/6/2015
     * @version 01.01.01
     */
    public function showProductEditForm($fields,$msg)
    {

        $product=new adminProductModel();
        $result=$product->getProductById($fields['Artists_products_id']);

        if($result['result']!='1')
        {
            $msg=$result['msg'];
            redirectPage(RELA_DIR . "admin/index.php?component=product", $msg);
        }

        $export=$product->fields;

        include_once(ROOT_DIR."component/category/admin/model/admin.category.model.php");
        $category = new adminCategoryModel();

        $resultCategory = $category->getCategoryOption();

        if($resultCategory['result'] == 1)
        {
            $export['category'] = $category->list;
        }
        /*echo '<pre/>';
        print_r($export);
        die();*/

        $this->fileName='admin.product.editForm.php';
        $this->template($export,$msg);
        die();
    }



    /**
     * @param $fields
     * @return mixed
     * @author malekloo
     * @date 3/6/2015
     * @version 01.01.01
     */
    public function showList($fields)
    {
        $product=new adminProductModel();
        $result=$product->getProduct($fields);
        if($result['result']!='1')
        {
            $this->fileName='admin.product.showList.php';
            $this->template('',$result['msg']);
            die();
        }
        $export['list']=$product->list;
        $export['artists_id']=$fields['choose']['artists_id'];


        $export['recordsCount']=$product->recordsCount;

        $this->fileName='admin.product.showList.php';
        $this->template($export);
        die();
    }
    /**
     * delete deleteCompany by company_id
     *
     * @param $id
     * @author malekloo
     * @date 2/24/2015
     * @version 01.01.01
     */
    public function deleteProduct($id)
    {

        $product = adminProductModel::find($id);

        if(!validator::required($id) and !validator::Numeric($id))
        {
            $msg= 'یافت نشد';
            redirectPage(RELA_DIR . "admin/index.php", $msg);
        }


        if(!is_object($product))
        {
            $msg=$product['msg'];
            redirectPage(RELA_DIR . "admin/index.php", $msg);
        }


        $company_id=$product->fields['artists_id'];


        $result=$product->delete();


        $dir = ROOT_DIR.'statics/files/'.$company_id.'/';
        fileRemover($dir,$product->fields['image']);

        if($result['result']!='1')
        {
            redirectPage(RELA_DIR . "admin/index.php?component=product&id=$company_id", $msg);
        }

        $msg='عملیات با موفقیت انجام شد';
        redirectPage(RELA_DIR . "admin/index.php?component=product&id=$company_id", $msg);
        die();
    }

}
?>