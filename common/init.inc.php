<?php

error_reporting(1);
error_reporting(E_ALL ^ E_STRICT ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING);
ini_set('display_errors',1);

$db = dbConn::getConnection();

$db->exec('SET character_set_database=UTF8');
$db->exec('SET character_set_client=UTF8');
$db->exec('SET character_set_connection=UTF8');
$db->exec('SET character_set_results=UTF8');
$db->exec('SET character_set_server=UTF8');
$db->exec('SET names UTF8');

/*** The SQL SELECT statement ***/
$sql = "SELECT * FROM web_config";

/*** fetch into an PDOStatement object ***/
$stmt = $db->query($sql);

/*** echo number of columns ***/
$obj = $stmt->fetchAll(PDO::FETCH_OBJ);

foreach( $obj as $v )
{

    if ( strtoupper($v->config) == "TITLE" )
    {
        define(strtoupper($v->config), ucwords(strtolower($v->value)) );
    }
    else
    {
        define(strtoupper($v->config), $v->value);
    }
}

include(ROOT_DIR . "common/breadcrumb.php");
$breadcrumb = new Breadcrumb();
$breadcrumbSearch = new Breadcrumb();

if(isset($_REQUEST['lang']))
{
    $_SESSION['lang'] = $_REQUEST['lang'];
    //$_REQUEST['currency']==$_SESSION['currency'];

}

if($_SESSION['lang'] == "" || !isset($_SESSION['lang']) || $_SESSION['lang']!='en')
{
    $_SESSION['lang'] ='fa'; // WEBSITE_LANGUAGE;
}
$_SESSION['lang'] ='fa'; // WEBSITE_LANGUAGE;

$lang = $_SESSION['lang'];

if($_REQUEST['color'] == 'white') { unset($_SESSION['themeColor']); header("location: ".RELA_DIR);  }
elseif($_REQUEST['color'] == 'black'){ $_SESSION['themeColor'] = '_black';  header("location: ".RELA_DIR);}



if($lang == 'en'){$cs = "template_ltr";}
else{$cs = "template_rtl{$_SESSION['themeColor']}";}
define('CURRENT_SKIN',$cs);

define('TEMPLATE_DIR',RELA_DIR."templates/".CURRENT_SKIN."/");
define('Count_Permission','20');

include(ROOT_DIR . "resource/language_$lang.inc.php");

include(ROOT_DIR . "common/message_stack.php");
include(ROOT_DIR . "common/data_stack.php");

include(ROOT_DIR . "component/login/model/login.model.php");

$login = new memberLogIn();

$member_info = $login->checkLogin();

global $messageStack;
$messageStack = new messageStack();
$messageStack->loadFromSession();
$dataStack = new dataStack();

//include(ROOT_DIR . "model/admin.class.php");

global $admin_info,$member_info;
//$admin = new admin();

//$admin_info = $admin->checkLogin();
//$member_info = $admin->checkLogin();

function __autoload($name)
{
    $modelFileName = ROOT_DIR . 'model/' . $name . '.class.php';
    $adminModelFileName = ROOT_DIR . 'model/admin.' . $name . '.class.php';

    if (file_exists($modelFileName)) {
        require_once($modelFileName);
    } elseif (file_exists($adminModelFileName)) {
        require_once($adminModelFileName);
    }
}
//$member_info='';
//$member_info['member_id']=1;



?>
