<?php
/**
 * Created by PhpStorm.
 * User: marjani
 * Date: 3/06/2016
 * Time: 12:08 AM
 */

include_once(dirname(__FILE__)."/admin.team.model.php");

/**
 * Class teamController
 */
class adminTeamController
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
     * @param array $list
     * @param $msg
     * @return string
     */
    function template($list=[],$msg)
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
     * @param $fields
     */
    public function showList($fields)
    {
        $team = adminTeamModel::getAll()->getList();
        if($team['result']!='1')
        {
            $this->fileName='admin.team.showList.php';
            $this->template('',$team['msg']);
            die();
        }

        $export['list']=$team['export']['list'];

        $export['recordsCount']=$team['export']['recordsCount'];
        $this->fileName='admin.team.showList.php';
        $this->template($export);
        die();
    }

    /**
     * @param $fields
     * @param $msg
     */
    public function showTeamAddForm($fields,$msg)
    {


        $this->fileName='admin.team.addForm.php';
        $this->template($fields,$msg);
        die();
    }

    /**
     * @param $fields
     * @return mixed
     */
    public function addTeam($fields)
    {

        $team=new adminTeamModel();

        $result=$team->setFields($fields);


        if($result['result']==-1)
        {
            $this->showTeamAddForm($fields,$result['msg']);
            //return $result;
        }
        $team->save();

        if(file_exists($_FILES['image']['tmp_name'])){

            $type  = explode('/',$_FILES['image']['type']);

            $input['upload_dir'] = ROOT_DIR.'statics/team/';
            $result = fileUploader($input,$_FILES['image']);
            $team->image = $result['image_name'];
            $result = $team->save();
        }


        //$result=$team->add();

        if($result['result']!='1')
        {
            $this->showTeamAddForm($fields,$result['msg']);
        }
        $msg='عملیات با موفقیت انجام شد';
        redirectPage(RELA_DIR . "admin/index.php?component=team", $msg);
        die();
    }

    /**
     * @param $fields
     * @param $msg
     */
    public function showTeamEditForm($fields,$msg)
    {
        if(!validator::required($fields['Team_id']) and !validator::Numeric($fields['Team_id']))
        {
            $msg= 'یافت نشد';
            redirectPage(RELA_DIR . "admin/index.php?component=team", $msg);
        }

        $team = adminTeamModel::find($fields['Team_id']);

        if(!is_object($team))
        {
            $msg=$team['msg'];
            redirectPage(RELA_DIR . "admin/index.php?component=team", $msg);
        }

        $export=$team->fields;



        $this->fileName='admin.team.editForm.php';
        $this->template($export,$msg);
        die();
    }

    /**
     * @param $fields
     */
    public function editTeam($fields)
    {
        //$team=new adminTeamModel();

        if(!validator::required($fields['Team_id']) and !validator::Numeric($fields['Team_id']))
        {
            $msg= 'یافت نشد';
            redirectPage(RELA_DIR . "admin/index.php?component=team", $msg);
        }

        $team = adminTeamModel::find($fields['Team_id']);

        if(!is_object($team))
        {
            $msg=$team['msg'];
            redirectPage(RELA_DIR . "admin/index.php?component=team", $msg);
        }


        $result=$team->setFields($fields);



        if($result['result']!=1)
        {
            $this->showTeamEditForm($fields,$result['msg']);
        }



        $team->save();

        if(file_exists($_FILES['image']['tmp_name'])){

            $type  = explode('/',$_FILES['image']['type']);

            $input['upload_dir'] = ROOT_DIR.'statics/team/';
            $result = fileUploader($input,$_FILES['image']);
            fileRemover($input['upload_dir'],$team->fields['image']);
            $team->image = $result['image_name'];

            $result = $team->save();
        }




        if($result['result']!='1')
        {
            $this->showTeamEditForm($fields,$result['msg']);
        }
        $msg='عملیات با موفقیت انجام شد';
        redirectPage(RELA_DIR . "admin/index.php?component=team", $msg);
        die();
    }

    /**
     * delete team by team_id
     *
     * @param $fields
     * @author marjani
     * @date 3/06/2015
     * @version 01.01.01
     */
    public function deleteTeam($fields)
    {

        if(!validator::required($fields['Team_id']) and !validator::Numeric($fields['Team_id']))
        {

            $this->showTeamEditForm($fields,translate('not found'));
        }

        $obj = adminTeamModel::find($fields['Team_id']);

        if(!is_object($obj))
        {
            $msg=$obj['msg'];
            redirectPage(RELA_DIR . "admin/index.php?component=team", $msg);
        }

        $dir = ROOT_DIR.'statics/team/';
        fileRemover($dir,$obj->fields['image']);
        $result = $obj->delete();


        if($result['result']!=1)
        {
            $this->showTeamEditForm($fields,$result['msg']);
        }
        $msg='عملیات با موفقیت انجام شد';
        redirectPage(RELA_DIR . "admin/index.php?component=team", $msg);
        die();
    }
}
?>