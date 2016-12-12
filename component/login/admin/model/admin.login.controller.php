<?php

/**
 * Created by PhpStorm.
 * User: malek
 * Date: 2/20/2016
 * Time: 4:24 PM.
 */
include_once dirname(__FILE__).'/admin.login.model.php';

/**
 * Class articleController.
 */
class adminLoginController
{

    public $fileName;


    /**
     * articleController constructor.
     */

    public function template($list = array(), $msg='')
    {
        global $admin_info;

        include(ROOT_DIR . "templates/admin/template_start.php");
        include(ROOT_DIR . "templates/admin/template_header.php");
        include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/$this->fileName");
        include(ROOT_DIR . "templates/admin/template_footer.php");
        include(ROOT_DIR . "templates/admin/template_end.php");
        die();
    }
    /**
     * show all article.
     *
     * @param $_input
     *
     * @author marjani
     * @date 2/28/2016
     *
     * @version 01.01.01
     */
    public function showLoginPage($fields='',$msg='')
    {
        $this->fileName='admin.login.form.php';
        $this->template($fields , $msg);
    }
    public function showPanel($fields='')
    {
        global $admin_info;
        $this->fileName='admin.index.php';
        $this->template($fields);

    }
    public function showRegisterPage($fields='',$msg='')
    {
        $this->fileName='members.register.php';
        $this->template($fields,$msg);
    }
    public function registerPage($fields)
    {
        $pass = $fields['password'];
        $fields['password'] = md5($pass);
        $data=adminLoginModel::getBy_username($fields['username']);
       // print_r_debug($data);
        $dataResult = $data->getList();
        //print_r_debug($dataResult);
        if($dataResult['result']!=1)
        {
            $msg='مشکلی در ثبت نام به وجود آمده';
            $this->showRegisterPage($fields,$msg);
            die();
        }
        if($dataResult['export']['recordsCount']!= 0)
        {
             $msg='نام کاربری وارد شده تکراری می باشد.';
            $this->showRegisterPage($fields,$msg);
            die();
        }

       //die('error');
      // print_r_debug($data);
        $dataSet=$data->setFields($fields);

        //die('error');
        if($dataSet['result']!=1)
        {
            $msg='مشکلی در ثبت نام به وجود آمده';
            $this->showRegisterPage($fields , $msg);
            die();
        }
        $validate= $data->validator();
        //print_r_debug($validate);
        if($validate['result']!=1)
        {
            $this->showRegisterPage($fields,$validate['msg']);
            die();
        }
        $data->save();
        //print_r_debug($data->save());

        redirectPage(RELA_DIR.'login');
    }
    public function showSendPassword($fields='',$msg='')
    {
        $this->fileName='members.forgotPassword.php';
        $this->template($fields);
    }
    public function checkAgainPassword($fields)
    {
        $data=adminLoginModel::getBy_username_and_email($fields['username'],$fields['email'])->get();
        //print_r_debug($data);
        if($data['result']!=1)
        {
            $this->showSendPassword($fields);
        }
        if($data['export']['recordsCount'] !=1)
        {
            $msg='نام کاربری یا ایمیل ارسالی صحیح نمی باشد';
            $this->showSendPassword($fields,$msg);
            die();
        }
        else
        {
            $dataGet=$data['export']['list'][0];
        }
        // print_r_debug($dataGet);
        $pass= rand(1000,10000);
        $fields['password']=md5($pass);
        $result=$dataGet->setFields($fields);
        if($result['result']!=1)
        {
            $msg='وجود خطا در روند ارسال وجود دارد';
            $this->showSendPassword($fields,$msg);
        }

        $dataGet->save();
        $msg= 'رمز عبور جدید شما'.$pass .'است';
        $title='رمز جدید صفحه کاربری:';
        $finally=mail($fields['email'] , $title , $msg);
        // print_r_debug($finally);
        redirectPage(RELA_DIR.'admin');

    }
    public function login($fields)
    {
        global $admin_info;
        $fields['password']=md5($fields['password']);
        include_once ROOT_DIR.'component/admin/admin/model/admin.admin.model.php';
        $data= admin::getBy_username_and_password($fields['username'],$fields['password'])->get();

        if($data['result']!=1)
        {
            $this->showLoginPage($fields);
            die();
        }

        if($data['export']['recordsCount']!=1)
        {
            $msg='نام کاربری موجود نمی باشد';
            $this->showLoginPage($fields,$msg);
            die();
        }
        else
        {
            $dataLogin=$data['export']['list'][0];
            $check=$dataLogin->fields['Admin_id'];
        }


        $sessionField['admin_id'] = $check;
        $sessionField['remember_me'] = $fields['remember'];

        $_SESSION["sessionID"] = $this->encrypt( $check , $this->GetHash());

        if ($fields['remember'])
        {
            setcookie("sessionID", $_SESSION["sessionID"], time() + 2592000, "/", $_SERVER['HTTP_HOST']); // 1 month
        }
        else {
            setcookie("sessionID", $_SESSION["sessionID"], time() + 3600, "/", $_SERVER['HTTP_HOST']);
        }
        $sessionField['browser_session'] = $_SESSION["sessionID"] ;
        //$sessionField['last_access_time'] = NOW() ;

        include_once ROOT_DIR.'component/login/admin/model/admin.login.model.php';

        $session= adminLoginModel::getBy_browser_session($_SESSION["sessionID"])->get();

        if($session['result'] == 1 && $session['export']['recordsCount']!=0)
        {
            $updateClass=$session['export']['list'][0];
            $dataSession=$updateClass->setFields($sessionField);
        }
        else
        {
            $updateClass = new adminLoginModel();
            $dataSession=$updateClass->setFields($sessionField);
        }

        if($dataSession['result']!=1)
        {
            $this->showLoginPage($fields);
            die();
        }
        $result = $updateClass->save();

        $admin_info = $this->checkLogin();

        $this->fileName='admin.index.php';
        $this->template($fields);
    }

    function encrypt($string, $key)
    {
        $result = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char    = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char    = chr(ord($char) + ord($keychar));
            $result .= $char;
        }

        return base64_encode($result);
    }
    private static function GetHash()
    {
        return '%%1^^@@REWcmv21))--';
    }
    function decrypt($string, $key)
    {
        $result = '';
        $string = base64_decode($string);

        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result .= $char;
        }
        return $result;
    }

    public function showEditAdmin($fields='',$msg='')
    {
        global $member_info;
        //print_r_debug($member_info);
        //$showEdit = adminLoginModel::getBy_Members_id($member_info['Members_id'])->getList();
        $showEdit = adminLoginModel::find($member_info['Members_id']);
        //print_r_debug( $showEdit);

        if(!is_object($showEdit))
        {

        }
        $sourceEdit['data']= $showEdit->fields;
      //print_r_debug($sourceEdit['data']);
        $this->fileName='members.edit.php';
        $this->template($sourceEdit['data']);

    }
    public function editAdmin($fields)
    {
        global $member_info;
        $editAdmin = adminLoginModel::find($member_info['Members_id']);
        $check=$editAdmin->setfields($fields);
        if($check['result']!=1)
        {
            $msg='اطلاعات شما یافت نشد';
            $this->showEditAdmin($fields,$msg);
            die();
        }
        $validate=$editAdmin->validator();
        if($validate['result']!=1)
        {
            $this->showEditAdmin($fields,$validate['msg']);
            die();
        }
        $editAdmin->save();
        redirectPage(RELA_DIR.'admin');
    }
    public function showChangePassword($fields='' , $msg='')
    {
       $this->fileName='members.changePassword.php';
       $this->template($fields , $msg);
    }
    public function changePassword($fields)
    {
        global $member_info;
        if($fields['confirm'] != $fields['re_password'])
        {
            $msg='';
            $this->showChangePassword($fields,$msg);
            die();
        }
        $fields['password']=md5($fields['password']);
        $saveFields['password']=md5($fields['re_password']);
        $change=adminLoginModel::getBy_password_and_Members_id($fields['password'],$member_info['Members_id'])->get();
        $changePass=$change['export']['list'][0];
        if($fields['password'] != $changePass->fields['password'])
        {
            $msg= 'رمز وارد شده اشتباه می باشد';
            $this->showChangePassword($fields,$msg);
            die();
        }
        else
        {

           $setPass=$changePass->setfields($saveFields);
            //print_r_debug($setPass);
            if($setPass['result']!=1)
            {
                $msg='وجود خطا در انجام تغییر رمز';
                $this->showChangePassword($fields,$msg);
            }
            $changePass->save();
            redirectPage(RELA_DIR.'admin');
        }
    }
    public function logout()
    {
       global $admin_info;
       $exit=adminLoginModel::getBy_browser_session_and_admin_id($_SESSION["sessionID"],$admin_info['Admin_id'] )->get();
        if( $exit['result']!=1)
        {
            header('Location: '.RELA_DIR.'admin/');
            die();
        }
        if($exit['export']['recordsCount'] != 0)
        {
            $exitLogout=$exit['export']['list'][0];
            $exitLogout->delete();
        }
        $admin_info = -1;
        
        header('Location: '.RELA_DIR.'admin/');
    }

    public function checkLogin()
    {

        if (!isset($_SESSION["sessionID"]))
        {
            if (!isset($_COOKIE["sessionID"]))
            {
                return -1;
            }
            else {
                $sessionMemberID = $this->decrypt($_COOKIE["sessionID"] , $this->GetHash());
            }
        }
        else {
            $sessionMemberID = $this->decrypt($_SESSION["sessionID"] , $this->GetHash());

        }
        // print_r( $sessionMemberID);
        $sessionMember = adminLoginModel::getBy_admin_id_and_browser_session($sessionMemberID,$_SESSION["sessionID"])->get();

        if($sessionMember['result']!=1 || $sessionMember['export']['recordsCount']!=1)
        {
            return -1;
        }

        $dataMember=$sessionMember['export']['list'][0];
        $finally= $dataMember->fields['admin_id'];
        //print_r( $finally);
        include_once ROOT_DIR.'component/admin/admin/model/admin.admin.model.php';
        $member=admin::find($finally);

        if(! is_object($member))
        {
            return -1;
        }


        return $member->fields;
    }
}
