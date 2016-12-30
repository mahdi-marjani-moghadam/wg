<?php
/**
 * Created by PhpStorm.
 * User: malek
 * Date: 2/20/2016
 * Time: 4:24 AM
 */

include_once(dirname(__FILE__)."/news.model.php");

class notFoundController
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

    public function __construct()
    {
        $this->exportType='html';

    }
    function template($list=array(),$msg)
    {
        global $PARAM, $member_info;

        switch($this->exportType)
        {
            case 'html':

                include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/title.inc.php");
                include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/$this->fileName");
                include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/tail.inc.php");
                break;

            case 'json':
                echo json_encode($list);
                break;

            case 'serialize':
                 echo serialize($list);
                break;
            default:
                break;
        }

    }
    public function show($_input)
    {

        $this->fileName='404.php';
        $this->template($_input);
        die();
    }




}
?>