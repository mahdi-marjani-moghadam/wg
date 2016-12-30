<?php
/**
 * @author Malekloo Izadi Sakhamanesh <Izadi@dabacenter.ir>
 * @version 0.0.1 this is the beta version of News
 * @copyright 2015 The Imen Daba Parsian Co.
 */
class admin_member_db extends DataBase
{

    /** Contains each field
     * @var
     */
    private $_Fields;
    private $_priceFields;






    /**
     * Contains company list
     * @var array
     */
    private $_list;

    /**
     * Contains company list
     * @var array
     */
    private $_paging;
    /**
     * Contains company list
     * @var array
     */
    private $_IDs;

    /**
     * Specifies the type of output
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    public function __construct()
    {
        $this->_companyListDb = array();
        $this->_groupCompany = array();
    }


    /**
     * Specifies the type of output
     * @param $method
     * @param $args
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    function __call($method, $args)
    {

        $method = '_' . $method;

        if (method_exists($this, $method)) {
            switch ($method) :
                case "_set_productFields" :
                    return $this->_set_productFields($args['0']);
                    break;
                case "_set_companyGroupFields" :
                    return $this->_set_companyGroupFields($args['0']);
                    break;
                case "_insertCompanyDB" :
                    return $this->_insertCompanyDB($args['0']);
                    break;
                case "_insertCompanyGroupDB" :
                    return $this->_insertCompanyGroupDB($args['0']);
                    break;
                case "_insertCompanyToGroupDB" :
                    return $this->_insertCompanyToGroupDB($args['0']);
                    break;
                case "_updateCompanyDB" :
                    return $this->_updateCompanyDB($args['0']);
                    break;
                case "_updateCompanyGroupDB" :
                    return $this->_updateCompanyGroupDB($args['0']);
                    break;
                case "_getCompanyById" :
                    return $this->_getCompanyById($args['0']);
                    break;
                case "_getCompanyGroupById" :
                    return $this->_getCompanygroupById($args['0']);
                    break;
                case "_getCompany" :
                    return $this->_getCompany($args['0']);
                    break;
                case "_getMembersList" :
                    return $this->_getMembersList($args['0'],$args['1']);
                    break;
                case "_getCompanyGroup" :
                    return $this->_getCompanyGroup($args['0']);
                    break;
                case "_removeCompanyDB" :
                    return $this->_removeCompanyDB($args['0']);
                    break;
                case "_removeFromGroupDB" :
                    return $this->_removeFromGroupDB($args['0']);
                    break;
                case "_set_IDs" :
                    return $this->_set_IDs($args['0']);
                    break;
                case "_changeStatusDB" :
                    return $this->_changeStatusDB($args['0']);
                    break;
                case "_changeGroupStatusDB" :
                    return $this->_changeGroupStatusDB($args['0']);
                    break;
                case "_trashCompanyDB" :
                    return $this->_trashCompanyDB($args['0']);
                    break;
                case "_recycleCompanyDB" :
                    return $this->_recycleCompanyDB($args['0']);
                    break;

            endswitch;
        }

    }


    /**
     * Specifies the type of output
     * @param $property
     * @param $value
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    public function __set($property, $value)
    {

       switch($property)
       {
           case '_groupCompany':
               $this->_groupCompany = $value;
               break;
       }

    }

    /**
     * Specifies the value of each field
     * @param $field
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    public function __get($field)
    {
        switch ($field) {

            case 'list':
                return $this->_list;
                break;
            case 'Fields':
                return $this->_Fields;
                break;
            case 'paging':
                return $this->_paging;
                break;
            default:
                break;
        }
    }


    /**
     * Specifies the type of output
     * @param $companyID
     * @param $value
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    private function _set_List($id, $value = '')
    {
        if (!empty($id) && is_numeric($id) && is_array($value))
        {
            $this->_list[$id] = $value;
        }
        $result['result'] = 1;

        return $result;

    }



    /**
     * Specifies the type of output
     * @param $insertedId
     * @param $value
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    private function _set_InsertCompanyGroupDB($insertedId, $value = '')
    {
        if (!empty($insertedId) && is_numeric($insertedId) && is_array($insertedId))
        {
            $this->_groupCompany[$insertedId] = $value;
        }

    }



    /**
     * Specifies the type of output
     * @param $value
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    public function set_Fields($value = '')
    {
        $this->_Fields = $value;
        $result['result'] = 1;
        $result['no'] = 1;
        return $result;
    }

    public function set_priceFields($value = '')
    {
        $this->_priceFields = $value;
        $result['result'] = 1;
        $result['no'] = 1;
        return $result;
    }
    /**
     * Insert news
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    public function updatePriceDB()
    {
        //global $lang;
        $conn = parent::getConnection();

        $member_id = $this->_priceFields['member_id'];

        $sql = "
           DELETE
           FROM 	`product_price`
		   WHERE    member_id= '$member_id'";


        $stmt = $conn->prepare($sql);
        $stmt->execute();
        if (!$stmt)
        {
            $result['result'] = -1;
            $result['no'] = 1;
            $result['msg'] = 'DB error : ' . $conn->errorInfo();
            return $result;
        }

        $sql = "
        INSERT INTO product_price(
        member_id,
        product_id,
        price
        )
        VALUES";
        foreach($this->_priceFields['price'] as $product_id => $price)
        {

            $sql .= "(
                        '" . $member_id . "',
                        '" . $product_id . "',
                        '" . $price . "'
                        ),";

        }
        $sql = substr($sql,0,-1);
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        if (!$stmt)
        {
            $result['result'] = -1;
            $result['no'] = 1;
            $result['msg'] = 'DB error : ' . $conn->errorInfo();
            return $result;
        }

        $result['result'] = 1;
        $result['Number'] = 2;
        return $result;
    }
    /**
     * Specifies the type of output
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    public function _checkPermission()
    {

    }




    /**
     * Gets each news based on its ID
     * @param $compID
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    public function getById($id)
    {
        //global $lang;
        $conn = parent::getConnection();
        $sql = "SELECT
                    *
                FROM
                    members
                WHERE
                    member_id= '$id'";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if (!$stmt)
        {
            $result['result'] = -1;
            $result['Number'] = 1;
            $result['msg'] = $conn->errorInfo();
            return $result;
        }

        if (!$stmt->rowCount())
        {
            $result['result'] = -1;
            $result['Number'] = 1;
            $result['msg'] = 'This Record was Not Found';
            return $result;
        }

        $row = $stmt->fetch();
        $this->set_Fields($row);
        $result['result'] = 1;
        return $result;

    }



    /**
     * Gets news
     * @return  mixed
     * @param mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    public function getAll($fields='')
    {
        //global $lang;
        $this->_checkPermission();
        $conn = parent::getConnection();
        $filter=$this->filterBuilder($fields);
        $length=$filter['length'];
        $filter=$filter['list'];

        $sql = "SELECT
                 *
    		     FROM 	members ".$filter['filter'].$filter['order'].$filter['limit'];



        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        if (!$stmt)
        {
            $result['result'] = -1;
            $result['no'] = 1;
            $result['msg'] = $conn->errorInfo();
            return $result;
        }

        $sql="
                SELECT COUNT(`members`.`member_id`) as recCount
                FROM
                `members`
              ".$filter['filter'];
        //echo $stmt->rowCount();

        $stmTp = $conn->prepare($sql);
        $stmTp->setFetchMode(PDO::FETCH_ASSOC);
        $stmTp->execute();
        $rowP = $stmTp->fetch();

        $rowFound=$rowP['recCount'];
        $this->_paging['recordsFiltered']=$rowP['recCount'];
        $this->_paging['recordsTotal']= $rowFound;

        while ($row = $stmt->fetch())
        {
            $this->_set_List($row['member_id'], $row);
        }



        $result['result'] = 1;
        $result['no'] = 2;
        return $result;

    }


    /**
     * Insert news
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    public function insertDB()
    {
        // global $lang;
        $conn = parent::getConnection();
        $sql = "
            INSERT INTO members(
            `username`,
            `password`,
            `name`,
            `family`,
            `mobile`,
            `phone`,
            `register_date`,
            `address`,
            status
            )
            VALUES(
            '" . $this->_Fields['username'] . "',
            '" . $this->_Fields['password'] . "',
            '" . $this->_Fields['name'] . "',
            '" . $this->_Fields['family'] . "',
            '" . $this->_Fields['mobile'] . "',
            '" . $this->_Fields['phone'] . "',
            '" . $this->_Fields['register_date'] . "',
            '" . $this->_Fields['address'] . "',
            '1'
            )";

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        if (!$stmt)
        {
            $result['result'] = -1;
            $result['no'] = 1;
            $result['msg'] = 'DB error : ' . $conn->errorInfo();
            return $result;
        }
        $insertedId = $conn->lastInsertId();
        $this->_Fields['member_id'] = $insertedId;
        //$this->_set_InsertCompanyDB($insertedId, $this->_productFields);
        $result['result'] = 1;
        $result['Number'] = 2;
        return $result;

    }




    /**
     * Insert news
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    public function updateDB()
    {
        //global $lang;
        $conn = parent::getConnection();
        $member_id = $this->_Fields['member_id'];

        $sql = "
                UPDATE members
                SET
                `username` =   '" . $this->_Fields['username'] . "',
                `name`='" . $this->_Fields['name'] . "',
                `family` =  '" . $this->_Fields['family'] . "',
                `mobile`=  '" . $this->_Fields['mobile'] . "',
                `phone`=  '" . $this->_Fields['phone'] . "',
                `password` =  '" . $this->_Fields['password'] . "',
                `status`=  '" . $this->_Fields['status'] . "'
                WHERE member_id = '$member_id'
                ";

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        if (!$stmt)
        {
            $result['result'] = -1;
            $result['no'] = 1;
            $result['msg'] = 'DB error : ' . $conn->errorInfo();

            return $result;
        }

        $result['result'] = 1;
        $result['Number'] = 2;
        return $result;
    }

    /**
     * Specifies the type of output
     * @param $compID
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    private function _removeCompanyDB($compID)
    {
        global $conn;
        $conn = parent::getConnection();

        $sql = "
           DELETE
           FROM 	tbl_company
		   WHERE    comp_id= '$compID'";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if (!$stmt)
        {

            $result['result'] = -1;
            $result['Number'] = 1;
            $result['msg'] = $conn->errorInfo();
            return $result;
        }

        $result['result'] = 1;
        return $result;

    }




}
