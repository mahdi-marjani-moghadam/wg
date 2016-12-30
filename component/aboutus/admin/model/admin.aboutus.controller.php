<?php
/**
 * Created by PhpStorm.
 * User: mahdi
 * Date: 12/22/2016
 * Time: 11:34 PM
 */

include_once(dirname(__FILE__)."/admin.aboutus.model.php");
class adminAboutusController
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
        $this->exportType = 'html';

    }

    /**
     * @param array $list
     * @param $msg
     * @return string
     */
    function template($list = [], $msg='')
    {
        global $messageStack;

        if($msg == '')
        {
            $msg = $messageStack->output('message');
        }


        switch ($this->exportType) {
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
     * @param $fields
     */
    public function showList($fields)
    {

        $aboutus = adminAboutusModel::getAll()->getList();
        if($aboutus['result']!='1')
        {
            $this->fileName='admin.aboutus.showList.php';
            $this->template('',$aboutus['msg']);
            die();
        }

        $export['list']=$aboutus['export']['list'];

        $export['recordsCount']=$aboutus['export']['recordsCount'];

        $this->fileName='admin.aboutus.showList.php';
        $this->template($export);
        die();
    }

    /**
     * @param $fields
     * @param $msg
     */
    public function showAboutusAddForm($fields,$msg)
    {


        $this->fileName='admin.aboutus.addForm.php';
        $this->template($fields,$msg);
        die();
    }

    /**
     * @param $fields
     * @return mixed
     */
    public function addAboutus($fields)
    {
        global $messageStack;
        $aboutus=new adminAboutusModel();

        $result=$aboutus->setFields($fields);


        if($result['result']==-1)
        {
            $this->showAboutusAddForm($fields,$result['msg']);
            //return $result;
        }
        $aboutus->save();

        if(file_exists($_FILES['image']['tmp_name'])){

            $type  = explode('/',$_FILES['image']['type']);

            $input['upload_dir'] = ROOT_DIR.'statics/aboutus/';
            $result = fileUploader($input,$_FILES['image']);
            if($result['result'] == -1)
            {
                $messageStack->add_session('message',$result['msg'],'error');
                redirectPage(RELA_DIR . "admin/index.php?component=aboutus", $result['msg']);
            }

            $aboutus->image = $result['image_name'];
            $result = $aboutus->save();
        }


        if($result['result']!='1')
        {
            $this->showAboutusAddForm($fields,$result['msg']);
        }

        $msg='عملیات با موفقیت انجام شد';
        $messageStack->add_session('message',$msg,'success');
        redirectPage(RELA_DIR . "admin/index.php?component=aboutus", $msg);
        die();
    }

    /**
     * @param $fields
     * @param $msg
     */
    public function showAboutusEditForm($fields,$msg)
    {
        if(!validator::required($fields['Aboutus_id']) and !validator::Numeric($fields['Aboutus_id']))
        {
            $msg= 'یافت نشد';
            redirectPage(RELA_DIR . "admin/index.php?component=aboutus", $msg);
        }

        $aboutus = adminAboutusModel::find($fields['Aboutus_id']);

        if(!is_object($aboutus))
        {
            $msg=$aboutus['msg'];
            redirectPage(RELA_DIR . "admin/index.php?component=aboutus", $msg);
        }

        $export=$aboutus->fields;



        $this->fileName='admin.aboutus.editForm.php';
        $this->template($export,$msg);
        die();
    }

    /**
     * @param $fields
     */
    public function editAboutus($fields)
    {
        global $messageStack;

        if(!validator::required($fields['Aboutus_id']) and !validator::Numeric($fields['Aboutus_id']))
        {
            $msg= 'یافت نشد';
            $messageStack->add_session('message',$msg,'error');
            redirectPage(RELA_DIR . "admin/index.php?component=aboutus", $msg);
        }

        $aboutus = adminAboutusModel::find($fields['Aboutus_id']);

        if(!is_object($aboutus))
        {
            $msg=$aboutus['msg'];
            $messageStack->add_session('message',$msg,'error');
            redirectPage(RELA_DIR . "admin/index.php?component=aboutus", $msg);
        }


        $result=$aboutus->setFields($fields);

        if($result['result']!=1)
        {
            $this->showAboutusEditForm($fields,$result['msg']);
        }

        $aboutus->save();

        if(file_exists($_FILES['image']['tmp_name'])){

            $type  = explode('/',$_FILES['image']['type']);

            $input['upload_dir'] = ROOT_DIR.'statics/aboutus/';
            $result = fileUploader($input,$_FILES['image']);
            fileRemover($input['upload_dir'],$aboutus->fields['image']);
            $aboutus->image = $result['image_name'];

            $result = $aboutus->save();
        }

        if($result['result']!='1')
        {
            $this->showAboutusEditForm($fields,$result['msg']);
        }
        $msg='عملیات با موفقیت انجام شد';
        redirectPage(RELA_DIR . "admin/index.php?component=aboutus", $msg);
        die();
    }

    /**
     * delete aboutus by aboutus_id
     *
     * @param $fields
     * @author marjani
     * @date 3/06/2015
     * @version 01.01.01
     */
    public function deleteAboutus($fields)
    {
        global $messageStack;
        if(!validator::required($fields['Aboutus_id']) and !validator::Numeric($fields['Aboutus_id']))
        {

            $this->showAboutusEditForm($fields,translate('not found'));
        }

        $obj = adminAboutusModel::find($fields['Aboutus_id']);

        if(!is_object($obj))
        {
            $msg=$obj['msg'];
            $messageStack->add_session('message',$msg,'error');
            redirectPage(RELA_DIR . "admin/index.php?component=aboutus", $msg);
        }

        $dir = ROOT_DIR.'statics/aboutus/';
        fileRemover($dir,$obj->fields['image']);
        $result = $obj->delete();


        if($result['result']!=1)
        {
            $messageStack->add_session('message',$result['msg'],'error');
            redirectPage(RELA_DIR . "admin/index.php?component=aboutus", $result['msg']);
        }
        $msg='عملیات با موفقیت انجام شد';
        redirectPage(RELA_DIR . "admin/index.php?component=aboutus", $msg);

    }


}