<?php
/**
 * Created by PhpStorm.
 * User: malekloo
 * Date: 3/6/2016
 * Time: 11:21 AM
 */

include_once(dirname(__FILE__)."/admin.category.model.php");

/**
 * Class registerController
 */
class adminCategoryController
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
    function template($list=[], $msg)
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
     * add honour
     *
     * @param $_input
     * @return int|mixed
     * @author marjani
     * @date 2/27/2015
     * @version 01.01.01
     */
    public function addCategory($_input)
    {

        $pub=new adminCategoryModel;
        print_r_debug($_input);
        $saveOption = $_input;
        $saveOption['category'] = (implode(",",$_input['category']));
        $saveOption['otherPic'] = (implode(",",$_input['otherPic']));
print_r_debug($saveOption);
        $result=$pub->setFields($saveOption);

        if($result['result']==-1)
        {
            $this->showCategoryAddForm($_input,$result['msg']);
            die();
        }
        $result = $pub->validator();

        $result=$pub->save();
        //print_r_debug($_FILES);
        foreach ($_FILES as $name=>$file)
        {//print_r_debug($file);

            if(is_array($file['tmp_name']))
            {}
            else
            {
                if(file_exists($file['tmp_name'])){

                    $type  = explode('/',$file['type']);
                    $input['max_size'] = $file['size'];
                    $input['upload_dir'] = ROOT_DIR.'statics/files/'.$_input['title'].'/';
                    $result = fileUploader($input,$file );
print_r_debug($result);
                    fileRemover($input['upload_dir'],$pub->fields['file']);

                    $pub->file_type = $type[0];
                    $pub->extension = $type[1];
                    $pub->file = $result['image_name'];
                    $result = $pub->save();
                }
            }
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

        print_r_debug($result);
        $msg='عملیات با موفقیت انجام شد';

        redirectPage(RELA_DIR . "admin/?component=category", $msg);
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

    public function showCategoryAddForm($fields ='' , $msg='' )
    {
        include_once(ROOT_DIR . "component/category/admin/model/admin.category.model.php");
        $category = new adminCategoryModel();
        $result = $category->getByFilter();
        if($result['result'] != 1)
        {
            $msg='مشکلی در نمایش بوجود آمده است.';

            redirectPage(RELA_DIR . "admin/?component=category", $msg);
        }
        $fields['category'] = $result['export']['list'];
        
        $this->fileName='admin.category.addForm.php';
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
    public function editCategory($fields)
    {

        $result=adminCategoryModel::find($fields['id']);

        $result1=$result->setFields($fields);

        if($result1['result']!=1)
        {
            $this->showCategoryEditForm($fields,$result['msg']);
            die();
        }

        $result=$result->save();


        $msg='ویرایش با موفقیت انجام شد.';
        redirectPage(RELA_DIR . "admin/?component=category", $msg);
        die();
    }


    /**
     * @param $fields
     * @return mixed
     * @author malekloo
     * @date 3/6/2015
     * @version 01.01.01
     */
    public function showCategoryEditForm($fields,$msg='')
    {

        $honour=new adminCategoryModel();
        $result=$honour::find($fields['id']);

        $export = $result->fields;

        $this->fileName='admin.category.editForm.php';
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
    public function showList($fields='')
    {
        $honour=new adminCategoryModel();
        $result=$honour->getByFilter();
        if($result['result']!='1')
        {
            $this->fileName='admin.category.list.php';
            $this->template('',$result['msg']);
            die();
        }
        $export['list']=$result['export']['list'];

        $export['recordsCount']=$result['export']['recordsCount'];
        $this->fileName='admin.category.list.php';
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
    public function deleteCategory($id)
    {

        $honour = adminCategoryModel::find($id);


        $result=$honour->delete();

        if($result['result']!='1')
        {
            redirectPage(RELA_DIR . "admin/index.php?component=category");
        }

        $msg='عملیات با موفقیت انجام شد';
        redirectPage(RELA_DIR . "admin/index.php?component=category", $msg);
        die();
    }

}

?>
