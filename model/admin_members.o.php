<?php

include_once(ROOT_DIR . "model/Validators.class.php");

/**
 * @author Malekloo Izadi Sakhamanesh <Izadi@dabacenter.ir>
 * @version 0.0.1 this is the beta version of News
 * @copyright 2015 The Imen Daba Parsian Co.
 */
class admin_member_operation
{
    /**
     * Contains Company info
     * @var
     */
    private $_Info;
    private $_priceInfo;

    /**
     * Contains Company info
     * @var
     */
   public $_paging;
    /**
     * Contains Company info
     * @var
     */

    private $_list;
    /**
     * @var
     */
    public  $_set;
    /**
     * Accessing the database
     * @var
     */
    private $_DbObj;
    /**
     * @var
     */
    private $_IDs;
   /**
     * @var
     */
    private $_trashList;


    /**
     * Specifies the type of output
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    public function __construct()
    {
        $this->_Info = array();
        $this->_priceInfo = array();
    }

    /**
     * Specifies the type of output
     * @param $property
     * @param $value
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @since 01.01.01
     * @date    08/08/2015
     */
    public function __set($property, $value)
    {
        switch($property)
        {
            default:
                break;
        }
    }

    /**
     * Specifies the type of output
     * @param $method
     * @param $args
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @since   08/08/2015
     * @date    08/08/2015
     */
    function __call($method, $args)
    {
        $method = '_' . $method;

        if (method_exists($this, $method)) {
            switch ($method) :
                case "_set_productInfo" :
                    return $this->_set_productInfo($args['0']);
                    break;
                case "_set_companyGroupInfo" :
                    return $this->_set_companyGroupInfo($args['0']);
                    break;
                case "_set_IDs" :
                    return $this->_set_IDs($args['0']);
                    break;
                case "_check" :
                    return $this->$method($args);
                    break;
                case "_getPointAction" :
                    return $this->$method($args[0]);
                    break;
                case "_deleteCompany" :
                    return $this->_deleteCompany($args['0']);
                    break;
                case "_deleteFromGroup" :
                    return $this->_deleteFromGroup($args['0']);
                    break;
                case "_getproductList" :
                    return $this->_getproductList($args['0']);
                    break;
                case "_getGroupMembersList" :
                    return $this->_getGroupMembersList($args['0'],$args['1']);
                    break;
                case "_getCompanyGroupList" :
                    return $this->_getCompanyGroupList($args['0']);
                    break;
                case "_getproductListById" :
                    return $this->_getCompanyListById($args['0']);
                    break;
                case "_getCompanyGroupListById" :
                    return $this->_getCompanyGroupListById($args['0']);
                    break;
                case "_insertCompany" :
                    return $this->_insertCompany($args['0']);
                    break;
                case "_insertCompanyToGroup" :
                    return $this->_insertCompanyToGroup($args['0']);
                    break;
                case "_insertCompanyGroup" :
                    return $this->_insertCompanyGroup($args['0']);
                    break;
                case "_updateCompany" :
                    return $this->_updateCompany($args['0']);
                    break;
                case "_updateCompanyGroup" :
                    return $this->_updateCompanyGroup($args['0']);
                    break;
                case "_changeStatus" :
                    return $this->_changeStatus($args['0']);
                    break;
                case "_changeGroupStatus" :
                    return $this->_changeGroupStatus($args['0']);
                    break;
                case "_trashCompany" :
                    return $this->_trashCompany($args['0']);
                    break;
                case "_recycleCompany" :
                    return $this->_recycleCompany($args['0']);
                    break;
                case "_checkAnnounceDependency" :
                    return $this->_checkAnnounceDependency($args['0']);
                    break;
                case "_checkIVRDependency" :
                    return $this->_checkIVRDependency($args['0']);
                    break;
                case "_checkQueueDependency" :
                    return $this->_checkQueueDependency($args['0']);
                    break;
                case "_checkExtensionDependency" :
                    return $this->_checkExtensionDependency($args['0']);
                    break;
                case "_checkUploadDependency" :
                    return $this->_checkUploadDependency($args['0']);
                    break;
                case "_checkSIPDependency" :
                    return $this->_checkSIPDependency($args['0']);
                    break;
                case "_checkInboundDependency" :
                    return $this->_checkInboundDependency($args['0']);
                    break;
                 case "_checkOutboundDependency" :
                    return $this->_checkOutboundDependency($args['0']);
                    break;


            endswitch;
        }

    }

    public function set_priceInfo($value='')
    {

        $result['result'] = 1;
        $this->_priceInfo=$value;

        return  $result;
    }

    public function set_Info($value='')
    {

        $result['result'] = 1;

        /**
         * Checks if the value of ID is not empty and is integer.
         */
        if (isset($value['member_id']))
        {
            if (empty($value['member_id']))
            {
                $msg='Please enter product id';

                if($result['result']==1)
                {
                    $result['msg'] = $msg;
                }
                $result['result'] = -1;
                $result['err'] = -2;

                $result['msgList']['member_id'] =  $msg;
            }
            elseif(!Validator::Numeric($value['member_id']))
            {
                $msg='member id should only contain numbers.';

                if($result['result']==1)
                {
                    $result['msg'] = $msg;
                }
                $result['result'] = -1;
                $result['err'] = -2;

                $result['msgList']['member_id'] =  $msg;
            }else
            {
                $this->_Info['member_id'] = $value['member_id'];
            }

        }

        if (isset($value['username']))
        {
            if (empty($value['username']))
            {
                $msg='Please enter  username';

                if($result['result']==1)
                {
                    $result['msg'] = $msg;
                }
                $result['result'] = -1;
                $result['err'] = -2;

                $result['msgList']['username'] =  $msg;
            }
            elseif(!is_string($value['username']))
            {
                $msg='username  should only contain characters.';

                if($result['result']==1)
                {
                    $result['msg'] = $msg;
                }
                $result['result'] = -1;
                $result['err'] = -2;

                $result['msgList']['username'] =  $msg;
            }
            else
            {
                $this->_Info['username'] = $value['username'];
            }

        }

        /**
         * Checks if the value of Company name is not empty and is string.
         */
        if (isset($value['name']))
        {
            if (empty($value['name']))
            {
                $msg='Please enter  name';

                if($result['result']==1)
                {
                    $result['msg'] = $msg;
                }
                $result['result'] = -1;
                $result['err'] = -2;

                $result['msgList']['name'] =  $msg;
            }
            elseif(!is_string($value['name']))
            {
                $msg='Comp name should only contain characters.';

                if($result['result']==1)
                {
                    $result['msg'] = $msg;
                }
                $result['result'] = -1;
                $result['err'] = -2;

                $result['msgList']['name'] =  $msg;
            }
            else
            {
                $this->_Info['name'] = $value['name'];
            }

        }
        if (isset($value['family']))
        {
            if (empty($value['family']))
            {
                $msg='Please enter  family';

                if($result['result']==1)
                {
                    $result['msg'] = $msg;
                }
                $result['result'] = -1;
                $result['err'] = -2;

                $result['msgList']['family'] =  $msg;
            }
            elseif(!is_string($value['family']))
            {
                $msg='family should only contain characters.';

                if($result['result']==1)
                {
                    $result['msg'] = $msg;
                }
                $result['result'] = -1;
                $result['err'] = -2;

                $result['msgList']['family'] =  $msg;
            }
            else
            {
                $this->_Info['family'] = $value['family'];
            }

        }

        if (isset($value['phone']))
        {
            if (empty($value['phone']))
            {
                $msg='Please enter  phone';

                if($result['result']==1)
                {
                    $result['msg'] = $msg;
                }
                $result['result'] = -1;
                $result['err'] = -2;

                $result['msgList']['phone'] =  $msg;
            }
            elseif(!is_string($value['phone']))
            {
                $msg='phone should only contain characters.';

                if($result['result']==1)
                {
                    $result['msg'] = $msg;
                }
                $result['result'] = -1;
                $result['err'] = -2;

                $result['msgList']['phone'] =  $msg;
            }
            else
            {
                $this->_Info['phone'] = $value['phone'];
            }

        }
        if (isset($value['mobile']))
        {
            if (empty($value['mobile']))
            {
                $msg='Please enter  mobile';

                if($result['result']==1)
                {
                    $result['msg'] = $msg;
                }
                $result['result'] = -1;
                $result['err'] = -2;

                $result['msgList']['mobile'] =  $msg;
            }
            elseif(!is_string($value['mobile']))
            {
                $msg='mobile should only contain characters.';

                if($result['result']==1)
                {
                    $result['msg'] = $msg;
                }
                $result['result'] = -1;
                $result['err'] = -2;

                $result['msgList']['mobile'] =  $msg;
            }
            else
            {
                $this->_Info['mobile'] = $value['mobile'];
            }

        }
        if (isset($value['password']))
        {

            if (empty($value['password']))
            {
                $msg='Please enter  password';

                if($result['result']==1)
                {
                    $result['msg'] = $msg;
                }
                $result['result'] = -1;
                $result['err'] = -2;

                $result['msgList']['password'] =  $msg;
            }
            elseif(!is_string($value['password']))
            {
                $msg='password should only contain characters.';

                if($result['result']==1)
                {
                    $result['msg'] = $msg;
                }
                $result['result'] = -1;
                $result['err'] = -2;

                $result['msgList']['password'] =  $msg;
            }
            else
            {
                $this->_Info['password'] = $value['password'];
            }

        }


        if (isset($value['status']))
        {
            if (empty($value['status']))
            {
                $msg='Please enter status.';

                if($result['result']==1)
                {
                    $result['msg'] = $msg;
                }
                $result['result'] = -1;
                $result['err'] = -2;

                $result['msgList']['status'] =  $msg;
            }
            elseif(!is_string($value['status']))
            {
                $msg='status should only contain characters.';

                if($result['result']==1)
                {
                    $result['msg'] = $msg;
                }
                $result['result'] = -1;
                $result['err'] = -2;

                $result['msgList']['status'] =  $msg;
            }
            else
            {
                $this->_Info['status'] = $value['status'];
            }

        }




        return  $result;

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

        switch($field)
        {
            case 'list':
                return $this->_list;
                break;
            case 'trashList':
                return $this->_trashList;
                break;
            case 'paging':
                return $this->_paging;
                break;
            case 'Info':
                return $this->_Info;
                break;
            case 'priceInfo':
                return $this->_priceInfo;
                break;

            default:
                break;
        }

    }


    /**
     * Gets the news list based on its ID
     * @param $compID
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */

    public function getById($member_id)
    {
        //global $conn, $lang;
        if (is_int($member_id))
        {
            $result['result']=-1;
            $result['no']=1;
            $result['msg']='Wrong ID';
            $result['func']='getCompanyListById';
            return $result;
        }

        include_once(ROOT_DIR . "model/admin_member.db.php");
        $this->_DbObj=new admin_member_db();
        $result=$this->_DbObj->getById($member_id);

        if($result['result']!=1)
        {
            return $result;
        }
        if ($result['result']==-1)
        {
            return $result;
        }

        $this->_Info = $this->_DbObj->Fields;
        $result['result'] = 1;
        $result['no'] = 2;
        return $result;
    }

    /**
     * Access the database class
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    private function getCompanyDbObj()
    {
        include_once(ROOT_DIR . "model/admin_product.db.class.php");
        $this->_companyDbObj=new company_db();
    }

    /**
     * Deletes Company
     * @param  $compID
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @since   08/08/2015
     * @date    08/08/2015
     */
    private function _deleteCompany($compID){
        //global $conn, $lang;
        $this->getCompanyDbObj();
        $result = $this->_companyDbObj->removeCompanyDB($compID);
        unset($this->_companyDbObj);

        if($result==-1)
        {
            $result['result'] = -1;
            $result['no'] = 2;
            return $result;
        }

        $result['result'] = 1;
        return $result;
    }



    public function getMemberList($fields)
    {


        //global $conn, $lang;
        //$this->getCompanyDbObj();
        include_once(ROOT_DIR . "model/admin_member.db.php");
        $this->_DbObj=new admin_member_db();
        $result=$this->_DbObj->getAll($fields);
        if($result['result']!=1)
        {
            return $result;
        }

        $this->_paging=$this->_DbObj->paging;
        $this->_list = $this->_DbObj->list;

        $this->_DbObj='';
        $result['result'] = 1;
        $result['no'] = 2;
        return  $result;
    }


    /**
     * Gets the news list
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01`
     * @date    08/08/2015
     */
    public function updatePrice()
    {
        include_once(ROOT_DIR . "model/admin_member.db.php");
        $this->_DbObj=new admin_member_db();

        $result=$this->_DbObj->set_priceFields($this->_priceInfo);
        if($result['result']==-1)
        {
            return $result;
        }

        $resultUpdate=$this->_DbObj->updatePriceDB();

        if($resultUpdate['result']==-1)
        {
            return $resultUpdate['msg'];
        }
        $result['result'] = 1;
        $result['msg'] = '?????? ?? ?????? ????? ??.';
        $result['no'] = 2;
        return $result;
    }


    /**
     * Gets the news list
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01`
     * @date    08/08/2015
     */
    public function update()
    {
        include_once(ROOT_DIR . "model/admin_member.db.php");
        $this->_DbObj=new admin_member_db();

        $result=$this->_DbObj->set_Fields($this->_Info);
        if($result['result']==-1)
        {
            return $result;
        }

        $resultUpdate=$this->_DbObj->updateDB();

        if($resultUpdate['result']==-1)
        {
            return $resultUpdate['msg'];
        }
        $result['msg'] = '?????? ?? ?????? ????? ??.';
        $result['no'] = 2;
        return $result;
    }



    /**
     * Gets the news list
     * @return  mixed
     * @author  Malekloo, Sakhamanesh, Izadi
     * @version 01.01.01
     * @date    08/08/2015
     */
    public function insertProduct()
    {
        include_once(ROOT_DIR . "model/admin_member.db.php");
        $this->_DbObj=new admin_member_db();
        //die('a');

        $result=$this->_DbObj->set_Fields($this->_Info);
        if($result['result']==-1)
        {
            return $result;
        }
        $resultInsert=$this->_DbObj->insertDB();

        if($resultInsert['result']==-1)
        {
            return $resultInsert['msg'];
        }

        $result=$this->set_Info($this->_DbObj->Fields);
        //print_r($this->_productInfo);

        //print_r($result);

        $result['result'] = 1;
        $result['no'] = 2;
        return $result;
    }

}