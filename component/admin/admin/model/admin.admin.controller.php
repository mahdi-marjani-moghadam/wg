<?php
/**
 * Created by PhpStorm.
 * User: daba
 * Date: 02-Oct-16
 * Time: 10:51 AM
 */
include_once dirname(__FILE__).'/admin.admin.model.php';


class adminAdminController
{
    public $exportType;

    public $fileName;

    public  function __construct()
    {
        $this->exportType='html';
    }

    public function template($list = [], $msg)
    {
        switch ($this->exportType)
        {
            case 'html':
                include ROOT_DIR . 'templates/' . CURRENT_SKIN . '/template_start.php';
                include ROOT_DIR . 'templates/' . CURRENT_SKIN . '/template_header.php';
                include ROOT_DIR . 'templates/' . CURRENT_SKIN . '/template_rightMenu_admin.php';
                include ROOT_DIR . 'templates/' . CURRENT_SKIN . "/$this->fileName";
                include ROOT_DIR . 'templates/' . CURRENT_SKIN . '/template_footer.php';
                include ROOT_DIR . 'templates/' . CURRENT_SKIN . '/template_end.php';
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


    public function showList($fields)
    {
        $admin=new adminadminModel();
        $result=$admin->getByFilter();
        if ($result['result'] != '1')
        {
            $this->fileName='admin.admin.showList.php';
            $this->template('',$result['msg']);
            die();
        }
        $export['list']=$result['export']['list'];
        $export['recordCount']=$result['export']['recordCount'];
        $this->fileName='admin.admin.showList.php';
        $this->template($export);
    }

    public function showAdminAddForm($fields, $msg)
    {
        $this->fileName = 'admin.admin.addForm.php';
        $this->template($fields, $msg);
        die();
    }

    public function addAdmin($fields)
    {
        if ($fields['password']) {
            $fields['password']=md5($fields['password']);
        }
        $admin1=adminadminModel::getBy_username($fields['username'])->first();
        if ($admin1->admin_id!='')
        {
            $msg = 'نام کاربری وجود دارد';
            $this->showAdminAddForm($fields,$msg);
        }
        else {
            $admin = new adminadminModel();
            $result = $admin->setFields($fields);
            $admin->save();
            if ($result['result'] == -1) {
                return $result;
            }
            if ($result['result'] != '1') {
                $this->showAdminAddForm($fields, $result['msg']);
            }
            $msg = 'عملیات با موفقیت انجام شد';
            redirectPage(RELA_DIR . 'admin/index.php?component=admin', $msg);
            die();
        }
    }
 
    public function editAdmin($fields)
    {
        $admin=adminadminModel::find($fields['admin_id']);
        if ($fields['password']!='') {
            $fields['password']=md5($fields['password']);
        }else
        {
            $fields['password']=$admin->password;
        }
        $admin->setFields($fields);
        $admin->save();
        $msg = 'عملیات با موفقیت انجام شد';
        redirectPage(RELA_DIR.'admin/index.php?component=admin', $msg);
        die();
    }

    public function showAdminEditForm($fields, $msg)
    {
        $admin=adminadminModel::find($fields['admin_id']);
        if(!is_object($admin))
        {
            $msg = 'صفحه مورد نظر یافت نشد';
            redirectPage(RELA_DIR.'admin/index.php?component=admin', $msg);
        }
        $export = $admin->fields;
        $this->fileName = 'admin.admin.editForm.php';
        $this->template($export, $msg);
        die();
    }

    public function deleteAdmin($fields)
    {
        $admin=adminadminModel::find($fields['admin_id']);
        $admin->delete();
        $msg='عملیات با موفقیت انجام شد';
        redirectPage(RELA_DIR.'admin/index.php?component=admin',$msg);
    }
}