<?php
error_reporting(1);
error_reporting(E_ALL ^ E_STRICT ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING);
ini_set('display_errors', 1);
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

if (isset($_REQUEST['lang'])) {
    $_SESSION['lang'] = $_REQUEST['lang'];
    //$_REQUEST['currency']==$_SESSION['currency'];

}
if ($_SESSION['lang'] == "" or !isset($_SESSION['lang']) or ($_SESSION['lang'] != 'en')) {

    $_SESSION['lang'] = 'fa'; // WEBSITE_LANGUAGE;
}
$lang = $_SESSION['lang'];

define('CURRENT_SKIN', "admin");
define('TEMPLATE_DIR', RELA_DIR . "templates/" . CURRENT_SKIN . "/");
define('Count_Permission','20');

include(ROOT_DIR . "resource/language_$lang.inc.php");


include(ROOT_DIR . "common/message_stack.php");
include(ROOT_DIR . "common/data_stack.php");
include_once(ROOT_DIR . "common/validators.php");

global $messageStack,$dataStack;
$messageStack = new messageStack();
$messageStack->loadFromSession();
$dataStack = new dataStack();

include(ROOT_DIR . "common/breadcrumb.php");

global $breadcrumb;
$breadcrumb = new breadcrumb();

include(ROOT_DIR . "component/login/admin/model/admin.login.model.php");

global $admin_info;
$admin = new adminLoginModel();

$admin_info = $admin->checkLogin();

//$Domain = $_SERVER['SERVER_NAME'];
//$nameDomain = explode('.', $Domain);


/*if ($admin->existCompany === NULL)
{
    die(INDEX_0078);//This Company is Not exist or inactive!!
}*/
//print_r($_SESSION);print_r($_REQUEST);echo "loooloo<br>";//die();


define('Count_Permission', '20');
// Calculate Expiry Company
//include_once(ROOT_DIR."model/produce.php");
//$Produce = clsProduce::getProduceCompany($_SESSION['compid']);



//$member_info = $admin->checkLogin();

//global $LANG;
$LANG = array("en", "fa");

// set company messages counter for show in right menu
// this code must put after set admin_info
/*if(checkPermissionsUI("page","ShowCompanyMessage") == 1) {
    include_once(ROOT_DIR . "model/page.model.php");
    $dynamicPage          = new DynamicPages();
    $countCompanyMessages = $dynamicPage->countCompanyMessage();
}*/
