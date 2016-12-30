<?php
/**
 * Created by PhpStorm.
 * User: malek
 * Date: 2/20/2016
 * Time: 4:24 PM
 */

include_once(dirname(__FILE__)."/admin.category.model.php");

/**
 * Class newsController
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
     *
     */
    public function __construct()
    {
        $this->exportType='html';

    }

    /**
     * @param string $list
     * @param $msg
     * @return string
     */
    function template($list=array(),$msg)
    {
        // global $conn, $lang;


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
     * @param $_input
     *
     */
    public function showMore($_input)
    {
        if(!is_numeric($_input))
        {
            $msg= 'یافت نشد';
            $this->template($msg);
        }
        $news=new adminNewsModel();
        $result=$news->getNewsById($_input);

        if($result['result']!=1)
        {
            die();
        }

        $this->template($news->fields);
        die();
    }


    public function getCategory_option($parent_id='0')
    {
        $model = new adminCategoryModel();
        $result=$model->getCategoryOption();

    }

        /**
     * @param $fields
     */
    public function showList($parent_id='0')
    {
        $model=new adminCategoryModel();



        $result=$model->getCategoryOption();

        if($result['result']!='1')
        {
            $this->fileName='admin.category.showList.php';
            $this->template('',$result['msg']);
            die();
        }

        $export['list']=$model->list;
        $export['recordsCount']=$model->recordsCount;
        $this->fileName='admin.category.showList.php';

        $this->template($export);

        die();

        foreach ($result as $key => $val)
        {
            print_r($val['export'].'<br/>');
        }
        //echo "<br/>start<br/>" . $st, "<br/>close<br/>";
        print_r($result);


        $result=$model->getCategoryTree();
        /*
         * //ul li sample
        $mainMenu=$model->getulli($model->list[$parent_id],1,$model->list);
        $mainMenu = "<ul>\n".$mainMenu ."</ul>";
        echo '<pre/>';
        print_r($mainMenu);*/

        $this->fileName='admin.news.showList.php';
        $this->template('',$result['msg']);
        die();

        $export['list']=$model->list;
        $export['recordsCount']=$news->recordsCount;
        $this->fileName='admin.news.showList.php';


        $fields = $result['export']['list'];
        $this->listCat = $fields;
        $mainMenu=$this->getulli($fields[0]);
        $mainMenu = "<ul>\n".$mainMenu ."</ul>";

        return $mainMenu;

        //////////////////////////
        if($result['result']!='1')
        {
            $this->fileName='admin.news.showList.php';
            $this->template('',$result['msg']);
            die();
        }
        $export['list']=$news->list;
        $export['recordsCount']=$news->recordsCount;
        $this->fileName='admin.news.showList.php';
        /////////////////////////



        //////
        if($result['result']!='1')
        {
            $this->fileName='admin.news.showList.php';
            $this->template('',$result['msg']);
            die();
        }
        $export['list']=$news->list;
        $export['recordsCount']=$news->recordsCount;
        $this->fileName='admin.news.showList.php';

        $this->template($export);
        die();
      //////



        if($result['result']!='1')
        {
            die();
        }
        $export['list']=$news->list;
        $export['recordsCount']=$news->recordsCount;
        $this->fileName='admin.news.showList.php';

        $this->template($export);
        die();
    }

    /**
     * @param $fields
     * @param $msg
     */
    public function showCategoryAddForm($fields,$msg)
    {


        $category = new adminCategoryModel();

        $resultCategory = $category->getCategoryOption('|-- ',0,'1');
        if($resultCategory['result'] == 1)
        {
            $fields['category'] = $category->list;
        }


        $this->fileName='admin.category.addForm.php';
        $this->template($fields,$msg);
        die();
    }

    /**
     * @param $fields
     * @return mixed
     */
    public function addCategory($fields)
    {
        $category=new adminCategoryModel();

        $fields['status'] = 1;
        $result = $category->setFields($fields);

        $valid = $category->validator();

        $category->save();


        $msg='عملیات با موفقیت انجام شد';
        redirectPage(RELA_DIR . "admin/index.php?component=category", $msg);
        die();
    }

    /**
     * @param $fields
     * @param $msg
     */
    public function showCategoryEditForm($fields,$msg)
    {

        $category=new adminCategoryModel();

        $result    = $category->getCategoryById($fields['Category_id']);

        if($result['result']!='1')
        {
            $msg=$result['msg'];
            redirectPage(RELA_DIR . "admin/index.php?component=category", $msg);
        }

        $export=$category->fields;

        $where="Category_id<>'{$fields['Category_id']}'";
        $resultCategory = $category->getCategoryOption('|-- ',0,'1',$where);
        if($resultCategory['result'] == 1)
        {
            $export['category_list'] = $category->list;
        }

        $this->fileName='admin.category.editForm.php';
        $this->template($export,$msg);
        die();
    }

    /**
     * @param $fields
     */
    public function editCategory($fields)
    {
        $object = adminCategoryModel::find($fields['Category_id']);
        if(!is_object($object))
        {
            $msg=$object['msg'];
            redirectPage(RELA_DIR . "admin/index.php?component=category", $msg);
        }
        $result=$object->setFields($fields);
        $result=$object->validator();

        $result = $object->save();


        if($result['result']!='1')
        {
            $this->showCategoryEditForm($fields,$result['msg']);
        }
        $msg='عملیات با موفقیت انجام شد';
        redirectPage(RELA_DIR . "admin/index.php?component=category", $msg);
        die();
    }
    public function deleteCategory($id)
    {

        $object = adminCategoryModel::find($id);

        if(!is_object($object))
        {
            $msg=$object['msg'];
            redirectPage(RELA_DIR . "admin/index.php?component=category", $msg);
        }


        $result=adminCategoryModel::getBy_parent_id($id)->get();

        if($result['export']['recordsCount']!='0')
        {
            $result['result'] = -1;
            $result['msg']='ابتدا زیر دسته ها را پاک نمایید';
            redirectPage(RELA_DIR . "admin/index.php?component=category", $msg);
        }

        $result = $object->delete();


        $msg='حذف دسته بندی';
        redirectPage(RELA_DIR . "admin/index.php?component=category", $msg);
        die();
    }

}
?>