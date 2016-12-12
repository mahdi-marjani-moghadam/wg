<?php
/**
 * Created by PhpStorm.
 * User: marjani
 * Date: 2/20/2016
 * Time: 4:24 PM
 * version:01.01.01
 */
class adminIndexModel
{
    /**
     * @var
     */
    private $TableName;

    /**
     * set fields by post arrived
     *
     * @var
     */
    private $fields;  // other record fields

    /**
     * @var
     */
    private $list;  // other record fields

    /**
     * @var
     */
    private $recordsCount;  // other record fields

    /**
     * @var
     */
    private $requiredFields;  // other record fields


    /**
     * @var
     */
    private $result;

    /**
     * adminIndexModel constructor.
     */
    public function __construct()
    {
        $this->requiredFields = array(
                                'title'=>  '',
                                'category_id'=>  '',
                                'brif_description'=>  '',
                                'description'=>  ''
                                );
    }

    /**
     *
     * @param $field
     * @return mixed
     * @author marjani
     * @date 2/28/2016
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
        else if ($field == 'recordsCount')
        {
            return $this->recordsCount;
        }

        else
        {
            return $this->fields[$field];
        }

    }

    /**
     * set the values that have been received through post
     *
     * @param $input
     * @return mixed
     * @author marjani
     * @date 2/28/2016
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
                if($result['result']==1)
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
     * set the values that have been received through post
     *
     * @param $input
     * @return mixed
     * @author marjani
     * @date 2/28/2016
     * @version 01.01.01
     */
    private function __setTitle ($input)
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

    /**
     * set the values that have been received through post
     *
     * @param $input
     * @return mixed
     * @author marjani
     * @date 2/28/2016
     * @version 01.01.01
     */
    private function __setCategory_id ($input)
    {

        if(!Validator::required($input) )
        {
            $result['result']=-1;
            $result['msg']='pleas select category';
        }else
        {
            $result['result'] = 1;
        }
        return $result;
    }

    /**
     * set the values that have been received through post
     *
     * @param $input
     * @return mixed
     * @author marjani
     * @date 2/28/2016
     * @version 01.01.01
     */
    private function __setBrif_description ($input)
    {
        if($input=='')
        {
            $result['result'] = 1;
        }else if(!Validator::required($input))
        {
            $result['result']=-1;
            $result['msg']='pleas enter Brif description';
        }else
        {
            $result['result'] = 1;
        }
        return $result;
    }

    /**
     * set the values that have been received through post
     *
     * @param $input
     * @return mixed
     * @author marjani
     * @date 2/28/2016
     * @version 01.01.01
     */
    private function __setDescription ($input)
    {
        if($input=='')
        {
            $result['result'] = 1;
        }else if(!Validator::required($input))
        {
            $result['result']=-1;
            $result['msg']='pleas enter Description';
        }else
        {
            $result['result'] = 1;
        }
        return $result;
    }

    /**
     * set the values that have been received through post
     *
     * @param $input
     * @return mixed
     * @author marjani
     * @date 2/28/2016
     * @version 01.01.01
     */
    private function __setMeta_keyword ($input)
    {
        if($input=='')
        {
            $result['result'] = 1;
        }else if(!Validator::required($input))
        {
            $result['result']=-1;
            $result['msg']='pleas enter Meta_keyword';
        }else
        {
            $result['result'] = 1;
        }
        return $result;
    }

    /**
     * set the values that have been received through post
     *
     * @param $input
     * @return mixed
     * @author marjani
     * @date 2/28/2016
     * @version 01.01.01
     */
    private function __setMeta_description ($input)
    {
        if($input=='')
        {
            $result['result'] = 1;
        }else if(!Validator::required($input))
        {
            $result['result']=-1;
            $result['msg']='pleas enter Meta_description';
        }else
        {
            $result['result'] = 1;
        }
        return $result;
    }

    /**
     * set the values that have been received through post
     *
     * @param $input
     * @return mixed
     * @author marjani
     * @date 2/28/2016
     * @version 01.01.01
     */
    private function __setDate ($input)
    {
        if($input=='')
        {
            $result['result'] = 1;
        }else if(!Validator::required($input))
        {
            $result['result']=-1;
            $result['msg']='pleas enter Date';
        }else
        {
            $result['result'] = 1;
        }
        return $result;
    }

    /**
     * set the values that have been received through post
     *
     * @param $input
     * @return mixed
     * @author marjani
     * @date 2/28/2016
     * @version 01.01.01
     */
    private function __setImage ($input)
    {
        if($input=='')
        {
            $result['result'] = 1;
        }else if(!Validator::required($input))
        {
            $result['result']=-1;
            $result['msg']='pleas enter Image';
        }else
        {
            $result['result'] = 1;
        }
        return $result;
    }

    /**
     * get index by id
     *
     * @param $id
     * @return mixed
     * @author marjani
     * @date 2/28/2016
     * @version 01.01.01
     */
    public function getIndexById($id)
    {
        include_once(dirname(__FILE__)."/admin.index.model.db.php");

        $result=adminIndexModelDb::getIndexById($id);

        if($result['result']!=1)
        {
            return $result;
        }

        /*$resultSet=$this->setFields($result['list']);
        if($resultSet!=1)
        {
            return $resultSet;
        }
        $result['result']=1;
        $result['list']= $this->fields;
        return $result;
        */
        //or

        $this->fields=$result['list'];
        return $result;
    }

    /**
     * get all index
     *
     * @param $fields
     * @return mixed
     * @author marjani
     * @date 2/28/2016
     * @version 01.01.01
     */
    public function getIndex($fields)
    {
        include_once(dirname(__FILE__)."/admin.index.model.db.php");
        include_once(ROOT_DIR."/component/category/admin/model/admin.category.model.php");


        $result=adminIndexModelDb::getIndex($fields);

        if($result['result']!=1)
        {
            return $result;
        }
        $this->list=$result['export']['list'];
        $this->recordsCount=$result['export']['recordsCount'];

        return $result;
    }

    /**
     * add new index
     *
     * @return mixed
     * @author marjani
     * @date 2/28/2016
     * @version 01.01.01
     */
    public function add()
    {

        foreach($this->requiredFields as $field =>$val)
        {

            if(!Validator::required($this->fields[$field]))
            {

                $result['result']=-1;
                $result['msg']="pleas enter $field";
                return $result;
            }
        }

        include_once(dirname(__FILE__)."/admin.index.model.db.php");
        $result=adminIndexModelDb::insert($this->fields);
        $this->fields['Index_id']=$result['export']['insert_id'];
        return $result;
    }

    /**
     * edit index by index_id
     *
     * @return mixed
     * @author marjani
     * @date 2/28/2016
     * @version 01.01.01
     */
    public function edit()
    {
        include_once(dirname(__FILE__)."/admin.index.model.db.php");
        $result=adminIndexModelDb::update($this->fields);
        return $result;
    }

    /**
     * delete index by index_id
     *
     * @return mixed
     * @author mahdi marjani moghadam <marjani@dabacenter.ir>
     * @date 2/28/2016
     * @version 01.01.01
     */
    public function delete()
    {
        include_once(dirname(__FILE__)."/admin.index.model.db.php");
        $result=adminIndexModelDb::delete($this->fields);
        return $result;
    }

}