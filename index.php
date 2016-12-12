<?php

include_once 'server.inc.php';
include_once ROOT_DIR.'common/db.inc.php';
include_once ROOT_DIR.'common/init.inc.php';
include_once ROOT_DIR.'common/func.inc.php';
include_once ROOT_DIR.'model/db.inc.class.php';
include_once ROOT_DIR.'common/looeic.php';




global $admin_info,$PARAM;
$url_main = substr($_SERVER['REQUEST_URI'], strlen(SUB_FOLDER) + 1);

$url_main = urldecode($url_main);

if (strlen($url_main) == 0) {
    $url_main = INDEX_URL;
}

$PARAM = explode('/', $url_main);
$PARAM = array_filter($PARAM, 'strlen');


if (array_search('exportType', $PARAM)) {
    $index_exportType = array_search('exportType', $PARAM);
    $exportType = $PARAM[$index_exportType + 1];
    unset($PARAM[$index_exportType]);
    unset($PARAM[$index_exportType + 1]);
    $PARAM = implode('/', $PARAM);
    $PARAM = explode('/', $PARAM);
    $PARAM = array_filter($PARAM, 'strlen');
}

if (array_search('page', $PARAM)) {
    $index_pageSize = array_search('page', $PARAM);
    $page = $PARAM[$index_pageSize + 1];
    unset($PARAM[$index_pageSize]);
    unset($PARAM[$index_pageSize + 1]);
    $PARAM = implode('/', $PARAM);
    $PARAM = explode('/', $PARAM);
    $PARAM = array_filter($PARAM, 'strlen');
}

/*if (isset($PARAM['0']) && $PARAM['0'] != 'index') {
    include_once ROOT_DIR.'component/city/admin/model/admin.city.model.db.php';
    $city = adminCityModelDb::getCityByNameArray($PARAM['0']);
    if ($city['result'] == '1') {
        $_SESSION['city'] = $PARAM['0'];
    }
}*/
/*if (isset($_SESSION['city'])) {
    if ($PARAM['0'] == 'index') {
        unset($_SESSION['city']);
    }
    if (isset($PARAM['1'])) {
        if ($PARAM['0'] == $_SESSION['city']) {
            $componenetAdress = ROOT_DIR."component/{$PARAM['1']}/{$PARAM['1']}.php";
        } else {
            $componenetAdress = ROOT_DIR."component/{$PARAM['0']}/{$PARAM['0']}.php";
        }
    } else {
        if ($PARAM['0'] == $_SESSION['city']) {
            $componenetAdress = ROOT_DIR.'component/index/index.php';
        } else {
            $componenetAdress = ROOT_DIR."component/{$PARAM['0']}/{$PARAM['0']}.php";
        }
    }
} else {*/

    $componenetAdress = ROOT_DIR."component/{$PARAM['0']}/{$PARAM['0']}.php";


//print_r_debug($PARAM);
/*}*/


if (!file_exists($componenetAdress)) {
    $componenetAdress = ROOT_DIR.'component/404/404.php';
}

include_once $componenetAdress;
