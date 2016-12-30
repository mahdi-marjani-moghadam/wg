<?php
/**
 * Created by PhpStorm.
 * User: marjani
 * Date: 2/27/2016
 * Time: 10:35 AM
 */
include_once(ROOT_DIR."/common/validators.php");
class contactusModel
{
    private $fields;  // other record fields
    private $list;  // other record fields

    private $result;

    /**
     * contactusModel constructor.
     */
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

    /**
     * @param $field
     * @return mixed
     * @author marjani
     * @date 2/27/2015
     * @version 01.01.01
     */
    public function __get($field)
    {
        if ($field == 'result')
        {
            return $this->result;
        }
        else if ($field == 'fields')
        {
            return $this->fields;
        }
        else if ($field == 'list')
        {
            return $this->list;
        }



        else
        {
            return $this->fields[$field];
        }

    }

    /**
     * validator controller
     *
     * @param $input
     * @return int
     * @author marjani
     * @date 2/27/2015
     * @version 01.01.01
     */
    public function setFields ($input)
    {
        foreach($input as $field =>$val)
        {
            $funcName='__set'.ucfirst($field);
            if(method_exists($this,$funcName))
            {
                $result=$this->$funcName($val);


                if($result['result'] == '1')
                {
                    $this->fields[$field]=$val;
                }else
                {

                    return $result;
                }

            }
        }
        $result['result']=1;
        return $result;

    }

    /**
     * check subject
     *
     * @param $input
     * @return mixed
     * @author marjani
     * @date 2/27/2015
     * @version 01.01.01
     */
    private function __setSubject ($input)
    {
        if(!Validator::required($input))
        {
            $result['result']=-1;
            $result['msg']='لطفا عنوان را وارد نمایید.';
        }else
        {
            $result['result'] = 1;
        }

        return $result;
    }

    /**
     *check email
     *
     * @param $input
     * @return mixed
     * @author marjani
     * @date 2/27/2015
     * @version 01.01.01
     */
    private function __setEmail ($input)
    {
        if(Validator::Email($input) != '1')
        {
            $result['result']=-1;
            $result['msg']='ایمیل را به درستی وارد نمایید.';
        }else
        {
            $result['result'] = 1;
        }

        return $result;
    }

    /**
     * check comment
     * @param $input
     * @return mixed
     * @author marjani
     * @date 2/27/2015
     * @version 01.01.01
     */
    private function __setComment ($input)
    {
        if(!Validator::required($input))
        {
            $result['result']=-1;
            $result['msg']='لطفا پیام را وارد نمایید.';
        }else
        {
            $result['result'] = 1;
        }

        return $result;
    }



    /**
     * add contact us
     *
     * @return mixed
     * @author marjani
     * @date 2/27/2015
     * @version 01.01.01
     */
    public function addContactus()
    {
        include_once(dirname(__FILE__)."/contactus.model.db.php");

        $result=contactusModelDb::insert($this->fields);


        if($result['result']!=1)
        {
            return $result;
        }

        $this->fields['Contact_id']=$result['export']['insert_id'];


        return $result;
    }




}