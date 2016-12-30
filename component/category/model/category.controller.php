<?php
/**
 * Created by PhpStorm.
 * User: malek
 * Date: 2/20/2016
 * Time: 4:24 PM.
 */
include_once dirname(__FILE__).'/category.model.php';

/**
 * Class newsController.
 */
class categoryController
{
    /**
     * Contains file type.
     *
     * @var
     */
    public $exportType;

    /**
     * Contains file name.
     *
     * @var
     */
    public $fileName;

    /**
     *
     */
    public function __construct()
    {
        $this->exportType = 'html';
    }

    /**
     * @param string $list
     * @param $msg
     *
     * @return string
     */
    public function template($list = array(), $msg)
    {
        // global $conn, $lang;

        switch ($this->exportType) {
            case 'html':

                //include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/template_start.tpl");
                //include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/template_header.tpl");
                //include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/template_rightMenu_admin.tpl");
                include ROOT_DIR.'templates/'.CURRENT_SKIN."/$this->fileName";
                //include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/template_footer.tpl");
                //include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/template_end.tpl");
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
     */
    public function showMore($_input)
    {
        if (!is_numeric($_input)) {
            $msg = 'یافت نشد';
            $this->template($msg);
        }
        $news = new adminNewsModel();
        $result = $news->getNewsById($_input);

        if ($result['result'] != 1) {
            die();
        }

        // breadcrumb
        global $breadcrumb;
        $breadcrumb->reset();
        $breadcrumb->add('دسته بندی');
        $breadcrumb->add($news['list']['title']);
        $export['breadcrumb'] = $breadcrumb->trail();

        $this->template($news->fields);
        die();
    }

    public function getCategory_option($parent_id = '0')
    {
        $model = new adminCategoryModel();
        $result = $model->getCategoryOption();
    }

    /**
     * @param $fieldzs
     */
    public function showList($parent_id = '0')
    {
        $model = new categoryModel();

        /*
         * sample1
         * $result=$model->getCategoryOption(0,'--');

        foreach ($result as $key => $val)
        {
            print_r($val['export'].'<br/>');
        }
        die();
        print_r($result);
        //end sample1
        */

         //ul li sample
        $parent_id = '0';
        $result = $model->getCategoryUlLi();
        echo $result['export']['list'];
        die();

        $this->fileName = 'admin.news.showList.php';
        $this->template('', $result['msg']);
        die();

        $export['list'] = $model->list;
        $export['recordsCount'] = $news->recordsCount;
        $this->fileName = 'admin.news.showList.php';

        $fields = $result['export']['list'];
        $this->listCat = $fields;
        $mainMenu = $this->getulli($fields[0]);
        $mainMenu = "<ul>\n".$mainMenu.'</ul>';

        return $mainMenu;

        //////////////////////////
        if ($result['result'] != '1') {
            $this->fileName = 'admin.news.showList.php';
            $this->template('', $result['msg']);
            die();
        }
        $export['list'] = $news->list;
        $export['recordsCount'] = $news->recordsCount;
        $this->fileName = 'admin.news.showList.php';
        /////////////////////////

        //////
        if ($result['result'] != '1') {
            $this->fileName = 'admin.news.showList.php';
            $this->template('', $result['msg']);
            die();
        }
        $export['list'] = $news->list;
        $export['recordsCount'] = $news->recordsCount;
        $this->fileName = 'admin.news.showList.php';

        $this->template($export);
        die();
      //////

        if ($result['result'] != '1') {
            die();
        }
        $export['list'] = $news->list;
        $export['recordsCount'] = $news->recordsCount;

        $this->fileName = 'admin.news.showList.php';
        $this->template($export);
        die();
    }

    /**
     * @param $fields
     * @param $msg
     */
    public function showNewsAddForm($fields, $msg)
    {
        $this->fileName = 'admin.news.addForm.php';
        $this->template($fields, $msg);
        die();
    }

    /**
     * @param $fields
     *
     * @return mixed
     */
    public function addNews($fields)
    {
        $news = new adminNewsModel();

        $result = $news->setFields($fields);

        if ($result['result'] == -1) {
            return $result;
        }
        $result = $news->add();

        if ($result['result'] != '1') {
            $this->showNewsAddForm($fields, $result['msg']);
        }
        $msg = 'عملیات با موفقیت انجام شد';
        redirectPage(RELA_DIR.'admin/index.php?component=news', $msg);
        die();
    }

    /**
     * @param $fields
     * @param $msg
     */
    public function showNewsEditForm($fields, $msg)
    {
        $news = new adminNewsModel();

        if (!validator::required($fields['News_id']) and !validator::Numeric($fields['News_id'])) {
            $msg = 'یافت نشد';
            redirectPage(RELA_DIR.'admin/index.php?component=news', $msg);
        }
        $result = $news->getNewsById($fields['News_id']);

        if ($result['result'] != '1') {
            $msg = $result['msg'];
            redirectPage(RELA_DIR.'admin/index.php?component=news', $msg);
        }

        $export = $news->fields;

        $this->fileName = 'admin.news.editForm.php';
        $this->template($export, $msg);
        die();
    }

    /**
     * @param $fields
     */
    public function editNews($fields)
    {
        $news = new adminNewsModel();

        if (!validator::required($fields['News_id']) and !validator::Numeric($fields['News_id'])) {
            $msg = 'یافت نشد';
            redirectPage(RELA_DIR.'admin/index.php?component=news', $msg);
        }
        $result = $news->getNewsById($fields['News_id']);
        if ($result['result'] != '1') {
            $msg = $result['msg'];
            redirectPage(RELA_DIR.'admin/index.php?component=news', $msg);
        }

        $result = $news->setFields($fields);

        if ($result['result'] != 1) {
            $this->showNewsEditForm($fields, $result['msg']);
        }

        $result = $news->edit();

        if ($result['result'] != '1') {
            $this->showNewsEditForm($fields, $result['msg']);
        }
        $msg = 'عملیات با موفقیت انجام شد';
        redirectPage(RELA_DIR.'admin/index.php?component=news', $msg);
        die();
    }
}
