<?php
/**
 * Created by PhpStorm.
 * User: marjani
 * Date: 3/6/2015
 * Time: 10:35 AM
 */

include_once(ROOT_DIR."/common/validators.php");
class adminProductModel extends looeic
{
    protected $TABLE_NAME = 'artists_products';
    //private $fields;  // other record fields
    private $list;  // other record fields

    private $result;
    public $recordsCount;

    /**
     * adminRegisterModel constructor.
     */
    public function __construct()
    {

        $this->requiredFields = array(
            'product_name'=>  ''
        );
    }

    /**
     * @param $field
     * @return mixed
     * @author malekloo
     * @date 3/6/2015
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
     * add product us
     *
     * @return mixed
     * @author malekloo
     * @date 3/6/2015
     * @version 01.01.01
     */
    public function addProduct()
    {
        foreach($this->requiredFields as $field =>$val)
        {
            $requiredList[$field]=$this->fields[$field];
        }
        $result=$this->setFields($requiredList);
        if($result['result']==-1)
        {
            return $result;
        }

        include_once(dirname(__FILE__)."/admin.product.model.db.php");

        $result=adminProductModelDb::insert($this->fields);

        if($result['result']!=1)
        {
            return $result;
        }

        $this->fields['Product_id']=$result['export']['insert_id'];

        return $result;
    }


    /**
     * edit product by Product_id
     *
     * @return mixed
     * @author malekloo
     * @date 3/06/2015
     * @version 01.01.01
     */
    public function edit()
    {

        foreach($this->requiredFields as $field =>$val)
        {
            $requiredList[$field]=$this->fields[$field];
        }
        $result=$this->setFields($requiredList);
        if($result['result']==-1)
        {
            return $result;
        }

        include_once(dirname(__FILE__)."/admin.product.model.db.php");
        $result=adminProductModelDb::update($this->fields);

        return $result;
    }



    /**
     * get all product
     *
     * @param $fields
     * @return mixed
     * @author malekloo
     * @date 2/24/2015
     * @version 01.01.01
     */
    public function getProduct($fields)
    {
        include_once(dirname(__FILE__)."/admin.product.model.db.php");

        $result=adminProductModelDb::getProduct($fields);

        if($result['result']!=1)
        {
            return $result;
        }
        $this->list=$result['export']['list'];
        $this->recordsCount=$result['export']['recordsCount'];

        return $result;
    }

    /**
     * get getProductById
     *
     * @param $id
     * @return mixed
     */
    public function getProductById($id)
    {
        include_once(dirname(__FILE__)."/admin.product.model.db.php");

        $result=adminProductModelDb::getProductById($id);

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

        $this->fields=$result['export']['list'];
        return $result;
    }

    /**
     * get Product By Company Id
     *
     * @param $id
     * @return mixed
     */
    public function getProductByCompanyId($id)
    {
        include_once(dirname(__FILE__)."/admin.product.model.db.php");

        $result=adminProductModelDb::getProductByCompanyId($id);

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

        $this->list=$result['list'];
        $this->list=$result['export']['list'];
        $this->recordsCount=$result['export']['recordsCount'];

        return $result;
    }


}
