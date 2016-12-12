<?php

include_once dirname(__FILE__).'/model/admin.login.controller.php';
include_once ROOT_DIR.'common/looeic.php';

global $PARAM , $admin_info;

$membersRoot=new adminLoginController();

if( isset($_REQUEST['action']) )
{
      if($_REQUEST['action'] == 'register')
      {
         if(count($_POST)>0)
         {
              $membersRoot->registerPage($_POST);
         }
          else
          {
              $membersRoot->showRegisterPage();
          }
      }
      elseif($_REQUEST['action']=='forgotPassword')
      {
          if(count($_POST)>0)
          {
              $membersRoot->checkAgainPassword($_POST);
          }
          else
          {
              $membersRoot->showSendPassword();
          }
      }
      elseif($_REQUEST['action']=='edit')
      {
          if(isset($_POST['sub']) && $_POST['sub']== 'ویرایش' )
          {
              $membersRoot->editAdmin($_POST);
          }
          else
          {
              $membersRoot->showEditAdmin();
          }

      }
      elseif($_REQUEST['action']=='changePasssword')
      {
          if(isset($_POST['sub']) && $_POST['sub']== 'تغییر رمز'  )
          {
              $membersRoot->changePassword($_POST);
          }
          else
          {
              $membersRoot->showChangePassword();
          }

      }
      elseif($_REQUEST['action']=='logout')
      {
          $membersRoot->logout();
      }

}
if(isset($_POST['action']) && $_POST['action']=='login' )
{
    if($_POST['username'] == '' || $_POST['password'] == '')
    {
        $membersRoot->showLoginPage('' , 'نام کاربری و یا رمز عبور شما خالی است.');
        die();
    }
    else
    {
        $membersRoot->login($_POST);
    }
}

    if($admin_info == -1 || $admin_info =='')
    {
        $membersRoot->showLoginPage();
    }
    else
    {
        $membersRoot->showPanel();
    }

