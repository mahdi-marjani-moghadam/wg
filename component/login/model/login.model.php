<?php
class memberLogIn
{

    private $_rasParameter;
    private $_existCompany = '';
    private $_Domain = '';
    private $_Arguments;
    private $_requiredFields;
    public $fileName;
    /**
     * Contains file type.
     *
     * @var
     */
    public $exportType;


    /**
     * articleController constructor.
     */
    public function __construct()
    {
        $this->exportType = 'html';
        $this->_requiredFields = array("username","password","artists_name","logo");
    }

    public function __set($field, $value)
    {
        switch ($field) {
            case 'rasParameter':
                $this->_rasParameter = $value;
                break;
            case 'existCompany':
                $_Result = $this->_set_existCompany($value);
                break;
            case 'Domain':
                $_Result = $this->_set_Domain($value);
                break;
        }
    }

    private function _set_existCompany($id)
    {
        if (is_numeric($id)) {
            $this->_existCompany = $id;
        }
    }

    private function _set_Domain($id)
    {
        if (is_string($id)) {
            $this->_Domain = $id;
        }
    }

    public function __get($property)
    {
        if ($property == 'existCompany') {
            return $this->_get_existCompany();
        }
        else {
            return false;
        }
    }

    private function _get_existCompany()
    {
        return $this->_existCompany;
    }

    public function __call($methodName, $arguments)
    {

        $_Result = $this->_checkMethod($methodName);

        if ($_Result[0] == 1) {
            $_Result = $this->_set_Arguments($arguments);

            if ($_Result[0] == 1 || $_Result[0] == 0) {
                $methodName = '_' . $methodName;
                $_Result    = $this->$methodName();
                return ($_Result);
                die();
            }
            elseif ($_Result[0] == -1) {
                redirectPage(RELA_DIR . 'index.php', $_Result['errMsg']);
                die();
            }

        }
        elseif ($_Result[0] == 0) {
            redirectPage(RELA_DIR . 'index.php', $_Result['errMsg']);
            die();
        }
    }

    private function _checkMethod()
    {
        $temp = func_get_args();
        if (method_exists($this, "_" . $temp[0])) {
            $_Result[0]     = 1;
            $_Result['Msg'] = "The mathod name is correct";
            return $_Result;
        }
        else {
            $_Result[0]        = 0;
            $_Result['errMsg'] = "The Method (" . $temp[0] . ") that you call is wrong";// For Test : The Method (".$temp[0].") that you call is wrong
            return $_Result;
        }
    }

    private function _set_Arguments()
    {
        $temp = func_get_args();
        if (!empty($temp[0])) {

            if (count($temp[0]) == 1) {
                if (!empty($temp[0][0])) {
                    $this->_Arguments = $temp[0][0];
                }
                else {
                    $_Result[0]        = -1;
                    $_Result['errMsg'] = "The arguments that you sent to class is empty";
                    return $_Result;
                }

            }
            elseif (count($temp[0]) > 1) {
                for ($i = 0; $i < count($temp[0]); $i++) {
                    if (!empty($temp[0][$i])) {
                        $this->_Arguments[$i] = $temp[0][$i];
                    }
                    else {
                        $this->_set_Arguments_toDefult($this->_Arguments);
                        $_Result[0]        = -1;
                        $_Result['errMsg'] = "The arguments that you sent to class is empty";
                        return $_Result;
                    }
                }

            }

            $_Result[0]     = 1;
            $_Result['Msg'] = "The _Arguments property seted successfully";
            return $_Result;

        }
        else {
            $_Result[0]     = 0;
            $_Result['Msg'] = "You Dont Sent Any Argument To Method";
            return $_Result;
        }
    }



    private static function GetHash()
    {
        return '%%1^^@@REWcmv21))--';
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

    function decrypt($string, $key)
    {
        $result = '';
        $string = base64_decode($string);

        for ($i = 0; $i < strlen($string); $i++) {
            $char    = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char    = chr(ord($char) - ord($keychar));
            $result .= $char;
        }

        return $result;
    }

    public function template($list = [], $msg='')
    {
        // global $conn, $lang;
        global $PARAM,$member_info,$lang,$messageStack;
        if($msg == '')
        {
            $msg2 = $messageStack->output('login');
        }

        switch ($this->exportType) {
            case 'html':

                include ROOT_DIR.'templates/'.CURRENT_SKIN.'/title.inc.php';
                include ROOT_DIR.'templates/'.CURRENT_SKIN."/$this->fileName";
                include ROOT_DIR.'templates/'.CURRENT_SKIN.'/tail.inc.php';
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


    function showLoginForm($fields , $msg ='')
    {
        /////// category
        include_once(ROOT_DIR."component/category/admin/model/admin.category.model.php");
        $category = new adminCategoryModel();

        $resultCategory = $category->getCategoryOption();

        if($resultCategory['result'] == 1)
        {
            $fields['category'] = $category->list;
        }
        //echo "<pre>";print_r($resultCategory);die();
        ///////

        include_once ROOT_DIR.'component/province/model/province.model.php';
        //$province = new adminProvinceModel();
        $province = province::getAll()->getList();

        //$resultProvince = $province->getStates();
        if ($province['result'] == 1) {
            $fields['provinces'] = $province['export']['list'];
        }



        $this->fileName = 'login.php';
        $this->template($fields, $msg);

        die();
    }

    function showChangePassForm($fields , $msg ='')
    {

        $this->fileName = 'changePass.login.php';
        $this->template($fields, $msg);

        die();
    }


    public function logIn($username = '', $password = '', $reffer = '')
    {
        global  $member_info, $messageStack;

        $conn = dbConn::getConnection();
        include_once ROOT_DIR.'/model/db.inc.class.php';

        if ($username == '') {


            $username = (handleData($_REQUEST["username"]));
        }
        if ($password == '') {

            $password = (handleData($_REQUEST["password"]));
        }


        $remember_me = 1;
        if ($username == "") {

            $result['result'] = -1;
            $result['msg'] = 'err_01' . '102 : Your Username Or Password Is Incorrect';
            return $result;
        }

        if (strlen($username) > 40 || checkUser($username)) {

            $result['result'] = -1;
            $result['msg'] = 'err_101 : Your Username Or Password Is Incorrect';
            return $result;
        }

        if ($password == "") {
            $result['result'] = -1;
            $result['msg'] = 'err_103 : Your Username Or Password Is Incorrect';
            return $result;
        }



        $sql = "DELETE FROM sessions
                    WHERE
                    (last_access_time < (NOW()-36000) and remember_me <> 1 )||  last_access_time < (NOW()-2592000) ";
        $stmt = $conn->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        if (!$stmt) {
            $result['result'] = -1;
            $result['no'] = 1;
            $result['msg'] = $conn->errorInfo();

            return $result;
        }


        $sql = "
                SELECT Artists_id 
                      ,status
				FROM   artists
				WHERE  username = '" . $username . "'
				AND    password = '" . md5($password) . "'
				";

        $stmt = $conn->prepare($sql);

        //$stmt->setFetchMode(PDO::FETCH_ASSOC);

        $stmt->execute();
        $row = $stmt->fetch();

        if (!$stmt) {
            $result['result'] = -1;
            $result['no'] = 1;
            $result['msg'] = $conn->errorInfo();

            return $result;
        }


        if ($stmt->rowCount() > 0 && $row['status'] == 1) {


            $sql = "INSERT INTO sessions (
                                        member_id ,
                                        remote_addr,
                                        last_access_time ,
                                        remember_me)
                VALUES ('" . $row['Artists_id'] . "',
                        '" . $_SERVER["SERVER_ADDR"] . "',
                        NOW() ,
                        '" . $remember_me . "')";

            $stmt = $conn->prepare($sql);
            $stmt->execute();


            $_SESSION["sessionID"] = $this->encrypt($conn->lastInsertId(), $this->GetHash());

            if ($remember_me) {

                setcookie("sessionID", $_SESSION["sessionID"], time() + 2592000, "/", $_SERVER['HTTP_HOST']); // 1 month
            }
            else {
                setcookie("sessionID", $_SESSION["sessionID"], time() + 3600, "/", $_SERVER['HTTP_HOST']);
            }


        }
        elseif ($stmt->rowCount() > 0 && $row['status'] == 0) {
            //if enter wrong password in login page add log to radPostAuth
            $result['result'] = -1;
            $result['msg'] = INDEX_0066 . " " . INDEX_0076;
            return $result;
        }
        else {
            $result['result'] = -1;
            $result['msg'] = "109 : " . LOGIN_PASSWORD1;
            return $result;
        }

        $result['result'] = 1;
        return $result;
    }


    function checkLogin()
    {

        global  $member_info;
        $conn = dbConn::getConnection();
        //print_r($_COOKIE["sessionID"]);
        if (!isset($_SESSION["sessionID"])) {
            if (!isset($_COOKIE["sessionID"])) {
                return -1;
            }
            else {
                $sessionID = $this->decrypt($_COOKIE["sessionID"], $this->GetHash());
            }
        }
        else {
            $sessionID = $this->decrypt($_SESSION["sessionID"], $this->GetHash());
        }

        $sql = "select `member_id`
                from   `sessions`
                where  `session_id` = '$sessionID'
                ";

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $row = $stmt->fetch();

        if (!$stmt) {
            $result['result'] = -1;
            $result['no'] = 1;
            $result['msg'] = $conn->errorInfo();

            return $result;
        }


        if ($stmt->rowCount() != 1) {
            return -1;
        }

        $sql = "select * from `artists`
                where `Artists_id` = " . $row['member_id'] . "
                ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        if (!$stmt) {
            $result['result'] = -1;
            $result['msg'] = $conn->errorInfo();
            return $result;
        }

        if ($stmt->rowCount() != 1) {
            return -1;
        }

        $member_info = $stmt->fetch();

        //added when enter wrong password
        unset($_SESSION['errorLogin']);


        return $member_info;
    }

    function logOut($return = false)
    {
        $conn = dbConn::getConnection();
        global  $member_info;

        if (!isset($_SESSION["sessionID"]) || strlen(($_SESSION["sessionID"])) < 5) {
            if (isset($_COOKIE['sessionID'])) {
                $_SESSION["sessionID"] = ($_COOKIE['sessionID']);
            }
        }

        if (isset($_SESSION["sessionID"])) {
            $sessionTable = $this->_checkLoginBySession();
            //$sessionID = $this->decrypt($_SESSION["sessionID"], $this->GetHash());
            $and = "AND session_id = '".$sessionTable['session_id']."'";
        }

        $sql = "delete from sessions 
                where       member_id ='" . $member_info["Artists_id"] . "'
                $and ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        if (!$stmt) {
            $result['result'] = -1;
            $result['no'] = 1;
            $result['msg'] = $conn->errorInfo();

            return $result;
        }

        if ($return == true) {
            $result['result'] = 1;
            return $result;
        }
        else {
            header("Location:" . RELA_DIR);
        }

        die();
    }


    function register($_input)
    {
        global $messageStack;
        include_once (ROOT_DIR."component/artists/model/artists.model.php");


        $result = artists::getBy_username($_input['username'])->getList();

        if($result['export']['recordsCount'] > 0)
        {
            $messageStack->add_session('register',translate('Exist user'));
            $this->showLoginForm($_input,translate('Exist user'));
        }



        $artists=new artists;



        if(isset($_input['category_id'])){
            $_input['category_id'] = ",".(implode(",",$_input['category_id'])).",";
        }
        $_input['refresh_date'] = date('Y-m-d h:i:s');
        $_input['password']  = md5($_input['password']);
        $artists->setFields($_input);
        $result = $artists->validator();


        if($result['result']==-1)
        {
            $this->showLoginForm($_input,translate($result['msg']));
            die();
        }

        $result=$artists->save();


        if(file_exists($_FILES['logo']['tmp_name'])){
            $input['upload_dir'] = ROOT_DIR.'statics/files/'.$artists->fields['Artists_id'].'/';
            $result = fileUploader($input,$_FILES['logo']);
            $artists->logo = $result['image_name'];
            $result = $artists->save();
        }


        if($result['result']!='1')
        {
            $messageStack->add_session('register',$result['msg']);
            $this->showLoginForm($_input,$result['msg']);
        }
        $msg='عملیات با موفقیت انجام شد';
        $messageStack->add_session('register',$msg);



        $result['msg'] = translate('Congratulation. You are registered successfuly.');
        return $result;
    }

    function registerValidate($fields)
    {
        include_once (ROOT_DIR."common/validators.php");

        $fieldsString  = $valuesString = '';
        foreach ($fields as $name => $value)
        {
            if(in_array($name,$this->_requiredFields) && Validator::required($value) == 0){

                $result['result'] = -1;
                $result['msg'] = INDEX_0127.' '.constant($name).' '.INDEX_0128;
                return $result;
            }

            if($name == 'password'){ $value = md5($value);}

            $fieldsString .= $name.',';
            if(is_array($value))
            {
                $category_id = '';
                foreach ($value as $k => $values)
                {
                    $category_id .= $values.",";
                }
                $valuesString .= "',".$category_id."',";
            }
            else
            {
                $valuesString .= "'".$value."',";
            }

        }

        $fieldsString = substr($fieldsString,0,-1);
        $valuesString = substr($valuesString,0,-1);



        $result['result'] = 1;
        $result['msg'] = 'ok';
        $result['fieldsString'] = $fieldsString;
        $result['valuesString'] = $valuesString;
        return $result;



    }

    private function _checkArtistsExist($username)
    {
        $conn = dbConn::getConnection();


        $sql = "select from  artists 
                WHERE
                username = '$username'";

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        if (!$stmt) {
            $result['result'] = -1;
            $result['no'] = 1;
            $result['msg'] = $conn->errorInfo();
            return $result;
        }

        if($stmt->rowCount() >0)
        {
            $result['result'] = -1;
            $result['msg'] = EXIST_USER;
            return $result;
        }

        $result['result'] = 1;
        return $result;

    }



    private function memberPage($message)
    {
        header("Location:" . RELA_DIR);
        echo $message;
    }

    /**
     * explain : check user online
     * @return mixed
     * @author faridcs
     * @date 12/16/2014
     * @version 01.01.01
     */
    private function _checkUserOnline()
    {
        global $conn, $member_info;
        $device = array();

        $sql = "SELECT * FROM `radacct`
                WHERE `acctstoptime` IS NULL
                AND `compid` = '" . $member_info['compid'] . "'
                AND `username` = '" . $member_info['username'] . "'";

        $deviceRS = $conn->Execute($sql);

        if (!$deviceRS) {
            echo $conn->ErrorMsg();
            die();
        }
        if ($deviceRS->RecordCount() != 0) {
            $checkOnline = 1;
        }
        else {
            $checkOnline = 0;
        }
        $deviceRS->close();

        return $checkOnline;
    }

    /**
     * explain : check user with device mac online
     * @author faridcs
     * @date 12/08/2014
     * @version 01.01.01
     * @param $macAddress
     * @param $username
     * @param $compId
     * @return int
     */
    private function checkOnline($macAddress, $username, $compId)
    {
        global $conn;

        $sql = "SELECT      *
                FROM        `radacct`
                WHERE       `acctstoptime` IS NULL
                AND         `username`         = '" . $username . "'
                AND         `callingstationid` = '" . $macAddress . "'
                AND         `compid`           = '" . $compId . "'
                " . "";

        $onlineRS = $conn->Execute($sql);
        if (!$onlineRS) {
            echo $conn->ErrorMsg();
            die();
        }

        if ($onlineRS->RecordCount() == 0) {

            $onlineRS->close();
            return 0;
        }
        else {
            $onlineRS->close();
            return 1;
        }
    }


    private function _checkLoginBySession()
    {
        $conn = dbConn::getConnection();


        if (!isset($_SESSION["sessionID"])) {
            if (!isset($_COOKIE["sessionID"])) {
                $result ['result'] = 0 ;
                $result ['msg'] = 'session Id not exists';
                return $result;
            }
            else {
                $sessionID = $this->decrypt($_COOKIE["sessionID"], $this->GetHash());
            }
        }
        else {
            $sessionID = $this->decrypt($_SESSION["sessionID"], $this->GetHash());
        }

        $sql = "SELECT          session_id
                FROM            `sessions`
                WHERE           `session_id`       = '" . $sessionID . "'";

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        if (!$stmt) {
            $result['result'] = -1;
            $result['no'] = 1;
            $result['msg'] = $conn->errorInfo();

            return $result;
        }

        if ($stmt->rowCount() != 1) {

            $result ['result'] = 0 ;
            $result ['msg'] = 'user is not login';
        }
        else
        {
            $row = $stmt->fetch();

            $result ['result'] = 1 ;
            $result ['msg'] = 'user is login';
            $result ['session_id'] = $row['session_id'];
        }

        return $result;
    }

   function sendPassword($fields)
   {
       global $member_info;

       include_once (ROOT_DIR.'component/artists/model/artists.model.php');
       $obj = artists::getBy_email($fields['email'])->get();


       if($obj['export']['recordsCount'] != 1)
       {
           $result['result'] = -1;
           $result['msg'] = translate('This user in not exist');
           return $result;
       }
       $obj1 = $obj['export']['list'][0];



       $code = uniqid();
       $url = RELA_DIR.'login/changePass/?email='.$obj['export']['list'][0]->fields['email'].'&code='.$code;

       sendmail($obj['export']['list'][0]->fields['email'],translate('Remember Password'),translate('Your change password link: ').$url);

       $obj1->forgot_code = $code;
       $obj1->save();


       $result['result'] = 1;
       $result['msg'] = translate('Send Password To Email.');
       return $result;

   }


   function checkCode($fields)
   {

       include_once (ROOT_DIR.'component/artists/model/artists.model.php');
       $obj = artists::getBy_email_and_forgot_code($fields['email'],$fields['code'])->get();
       if($obj['export']['recordsCount'] == 0)
       {
           $result['result'] = -1;
           $result['msg'] = translate('Information is wrong');
           return $result;
       }

       $result['result'] = 1;
       return $result;

   }
   function changePass($fields)
   {
       $result = $this->checkCode($fields);
       if($result['result'] == -1)
       {
           return $result;
       }

       if($fields['pass'] != $fields['pass_confirm'])
       {
           $this->showChangePassForm( $fields,translate('don`t match'));
       }

       include_once (ROOT_DIR.'component/artists/model/artists.model.php');
       $obj = artists::getBy_email_and_forgot_code($fields['email'],$fields['code'])->get();
       $obj1 = $obj['export']['list'][0];


       $obj1->password = md5($fields['pass']);
       $obj1->forgot_code = '';
       $obj1->save();




       $result['result'] = 1;
       $result['msg'] = translate('Password changed.');
       return $result;
   }


}
