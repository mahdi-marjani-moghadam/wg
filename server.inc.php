<?php
/**
 * Created by PhpStorm.
 * User: mahdi
 * Date: 9/13/16
 * Time: 4:07 PM
 */
//error_reporting(E_ALL ^ E_NOTICE);
//header("X-Powered-By: ASP.NET");
//ini_set('error_display',1);
//error_reporting(1);
session_start();
define("DB_TYPE","mysql");
// define("DB_HOST","172.18.205.250");
define("DB_HOST","localhost");
define("DB_USER","root");
define("DB_PASSWORD","root");
define("DB_DATABASE","webgem");
    define("ROOT_DIR",dirname(__FILE__) ."/");

define("SUB_FOLDER","");

define("RELA_DIR","http://".$_SERVER['HTTP_HOST']."/");

define("PRODUCT_IMAGE",RELA_DIR . "templates/images/product/product_image/");
define("PRODUCT_IMAGE_ROOT",ROOT_DIR . "templates/images/product/product_image/");
define("STATIC_ROOT_DIR",ROOT_DIR . "statics");

define("SMTP_SERVER","mail.dabacenter.ir");
define("SMTP_USERNAME","tehrani@dabacenter.ir");
define("SMTP_PASSWORD","");
define("SMTP_SENDER","Daba Center");

define("ADMIN_EMAIL","");



date_default_timezone_set('Asia/Tehran');


define("GAPI_KEY","AIzaSyBSLTeLs4MQpfJAF32OrTdEUTe-4T_rR_s");
?>
