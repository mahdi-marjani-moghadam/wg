<?php
/**
 * Created by PhpStorm.
 * User: malek
 * Date: 2/20/2016
 * Time: 4:24 PM
 */

include_once(dirname(__FILE__)."/index.model.php");

/**
 * Class articleController
 */
class indexController
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
     * articleController constructor.
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
    public function template($list=array(),$msg='')
    {
        global $PARAM,$member_info;
        //print_r($list['category_list']);
        //die();
        global $PARAM, $lang;

        switch($this->exportType)
        {
            case 'html':
                include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/title.inc.php");
                include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/$this->fileName");
                include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/tail.inc.php");
                break;

            case 'json':
                echo json_encode($list);
                break;
            case 'array':
                print_r_debug($list);
                break;

            case 'serialize':
                echo serialize($list);
                break;
            default:
                break;
        }

    }

    /**
     * show all article
     *
     * @param $_input
     * @author marjani
     * @date 2/28/2016
     * @version 01.01.01
     */
    public function showMore($_input)
    {
        if(!is_numeric($_input))
        {
            $msg= 'یافت نشد';
            $this->fileName = "article.showList.php";
            $this->template('',$msg);
            die();
        }
        $article=new articleModel;
        $result=$article->getArticleById($_input);

        if($result['result']!=1)
        {
            $this->fileName = "article.showList.php";
            $this->template('',$result['msg']);
            die();
        }
        $this->fileName = "article.showMore.php";
        $this->template($article->fields);
        die();
    }


    /**
     * get all article and  show in list
     *
     * @param $fields
     * @author marjani,malekloo
     * @date 2/28/2016
     * @version 01.01.02
     */
    public function showALL($fields)
    {

        //use category model func by getCategoryUlLi
        /*include_once(ROOT_DIR."component/category/model/category.model.php");
        $category = new categoryModel();

        $resultCategory = $category->getCategoryUlLi();

        if($resultCategory['result'] == 1)
        {
            $export['category_list'] = $resultCategory['export']['list'];
        }

        $resultCategoryAll = $category->allCategory();
        if ($resultCategoryAll['result'] == 1) {
            $export['category_list_all'] = $resultCategoryAll['export']['list'];
        }*/


        /*include_once(ROOT_DIR."component/banner/model/banner.model.php");
        $banner = new bannerModel();

        $fields['order']['priority']='ASC';
        $banner = $banner->getByFilter($fields);
        $export['banner'] = $banner['export']['list'];*/

        $this->fileName = "index.php";
        //print_r_debug($export);
        $this->template('');
        die();
    }

}
?>
