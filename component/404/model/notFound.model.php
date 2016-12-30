<?php
/**
 * Created by PhpStorm.
 * User: malek
 * Date: 2/20/2016
 * Time: 4:24 AM
 */
include_once(ROOT_DIR."/common/validators.php");
class newsModel
{
    private $TableName;
    public $fields;  // other record fields

    public function __construct()
    {
       /* $this->fields = array(
                                'title'=>  '',
                                'brif_description'=>  '',
                                'description'=>  '',
                                'meta_keyword'=>  '',
                                'meta_description'=>  '',
                                'image'=>  '',
                                'date'=>  ''
                                );*/
    }


    function __setFields ($input)
    {

        foreach($input as $field =>$val)
        {
            $funcName='__set'.ucfirst($field);
            if(method_exists($this,$funcName))
            {
                $result=$this->$funcName($val);
                if($result['result'])
                {
                    $this->fields[$field]=$val;
                }else
                {
                    return $result;
                }

            }
        }

    }

    function __setTitle ($input)
    {
        if(!Validator::required($input))
        {
            $result['result']=-1;
            $result['msg']='pleas enter title';
        }else
        {
            $result['result'] = 1;
        }

        return $result;
    }

    public function getById($id)
    {
        include_once(dirname(__FILE__)."/news.model.db.php");

        $result=newsModelDb::getById($id);


        if($result['result']==1)
        {
            //$this->fields=$result['list'];
            $this->__setFields($result['list']);
        }

        return $result;
    }


}