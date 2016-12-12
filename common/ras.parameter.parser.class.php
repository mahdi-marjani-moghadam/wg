<?php

require_once(ROOT_DIR . "common/validator.php");
/**
 * Class validators
 *@Auther Mahmoud Masih Tehrani
 *@Email tehrani@dabacenter.ir
 *@version 0.0.1
 */
class RasParser
{
    /**
	*
	* @param Array  $_Arguments           we use this parameter to save parameters that pass to private function from out of class
	*
	*/
    private		$_Arguments='';
    private		$_rasParameter='';


    public function __construct()
    {
        $this->_Arguments	  	  = array();
    }

    public function __set($field,$value)
    {
        switch ($field) :
            case "_Arguments" :
                $this->_set_Arguments($value);
                break;
            case "rasParameter" :
                $this->_set_rasParameter($value);
                break;
            default :
                $this->$field =  handleData($value);
        endswitch;
    }
    public function __get($field)
    {
        return $this->$field;
    }
    public function __call($methodName,$arguments)
    {


        $_Result = $this->_checkMethod($methodName);

        if($_Result[0]==1)
        {
            $_Result = $this->_set_Arguments($arguments);

            if($_Result[0]==1 || $_Result[0]==0)
            {
                $methodName = '_'.$methodName;
                $_Result = $this->$methodName();
                return($_Result);
                die();
            }
            elseif($_Result[0]==-1)
            {
                redirectPage(RELA_DIR.'index.php',$_Result['errMsg']);
                die();
            }


        }
        elseif($_Result[0]==0)
        {
            redirectPage(RELA_DIR.'index.php',$_Result['errMsg']);
            die();
        }
    }

    private function _checkMethod()
    {
        $temp = func_get_args();
        if(method_exists($this,"_".$temp[0]))
        {
            $_Result[0] = 1;
            $_Result['Msg'] = "The mathod name is correct";
            return $_Result;
        }
        else
        {
            $_Result[0] = 0;
            $_Result['errMsg'] = "The Method (".$temp[0].") that you call is wrong";// For Test : The Method (".$temp[0].") that you call is wrong
            return $_Result;
        }
    }
    private function _set_Arguments()
    {
        $temp = func_get_args();
        if(!empty($temp[0]))
        {

            if(count($temp[0])==1)
            {
                if(!empty($temp[0][0]))
                {
                    $this->_Arguments = $temp[0][0];
                }
                else
                {
                    $_Result[0] = -1;
                    $_Result['errMsg'] = "The arguments that you sent to class is empty";
                    return $_Result;
                }

            }
            elseif(count($temp[0])>1)
            {
                for($i=0;$i<count($temp[0]);$i++)
                {
                    if(!empty($temp[0][$i]))
                    {
                        $this->_Arguments[$i] = $temp[0][$i];
                    }
                    else
                    {
                        $this->_set_Arguments_toDefult($this->_Arguments);
                        $_Result[0] = -1;
                        $_Result['errMsg'] = "The arguments that you sent to class is empty";
                        return $_Result;
                    }
                }

            }

            $_Result[0] = 1;
            $_Result['Msg'] = "The _Arguments property seted successfully";
            return $_Result;

        }
        else
        {
            $_Result[0] = 0;
            $_Result['Msg'] = "You Dont Sent Any Argument To Method";
            return $_Result;
        }
    }

    private function _set_rasParameter()
    {
        $temp = func_get_args();

        if(count($temp)==1)
        {
            if(is_array($temp[0]) && count($temp[0]) > 1)
            {
                $this->_rasParameter['a'] = handleData($_POST['a']);
                foreach($temp[0] as $paramterName=>$value)
                {
                    if(isset($value))
                    {
                        switch($paramterName)
                        {
                            case 'mac':
                                $this->_rasParameter[$paramterName] = handleData($value);
                                break;
                            case 'ip':
                                if(Validators::IP($value))
                                {
                                    $this->_rasParameter[$paramterName] = handleData($value);
                                }
                                break;
                            case 'username':
                                $this->_rasParameter[$paramterName] = handleData($value);
                                break;
                            case 'link-login':
                                $this->_rasParameter[$paramterName] = handleData($value);
                                break;
                            case 'link-orig':
                                $this->_rasParameter[$paramterName] = handleData($value);
                                break;
                            case 'error':
                                $this->_rasParameter[$paramterName] = handleData($value);
                                break;
                            case 'error-orig':
                                $this->_rasParameter[$paramterName] = handleData($value);
                                break;
                            case 'chap-id':
                                $this->_rasParameter[$paramterName] = handleData($value);
                                break;
                            case 'chap-challenge':
                                $this->_rasParameter[$paramterName] = handleData($value);
                                break;
                            case 'link-login-only':
                                $this->_rasParameter[$paramterName] = handleData($value);
                                break;
                            case 'link-orig-esc':
                                $this->_rasParameter[$paramterName] = handleData($value);
                                break;
                            case 'mac-esc':
                                $this->_rasParameter[$paramterName] = handleData($value);
                                break;
                            case 'nasip':
                               ///////////////////// $nasip = '172.20.4.1'; !!?
                                $this->_rasParameter['nasIP'] = handleData('172.20.4.1');
                                break;

                        }
                    }


                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }

    }
    private function _set_rasParameterToDB()
    {
        global $conn;

        $sql = "INSERT INTO ras_parameter (`session_id`
                                           ,`parameters_json`
                                           )
                                    VALUES ('".session_id()."'
                                                  ,'".json_encode($this->_rasParameter)."'
                                                  )";

        //echo $sql;die();



        $rasParameterRS = $conn->Execute($sql);
        if(!$rasParameterRS)
        {
           $_Result['result'] = 0;
           $_Result['Msg'] = 'اشکال در بر قراری ارتباط با دیتا بیس می باشد.';
           $_Result['Err'] = 101;

        }

        $rasParameterRS->close();

        $_Result['result'] = 1;
        $_Result['Msg'] = 'ابا موفقیت در دیتا بیس ثبت گردید';
        $_Result['Err'] = 0;

        return $_Result;
        die();

    }
    private function _save()
    {



        $_Result = $this->_set_rasParameterToDB();

       switch($_Result['result'])
       {
           case '1':
               return $_Result;
               break;
           case '0' :
               $_Result['Msg'] = 'قابل بازیابی نیست';
               return $_Result;



       }

    }


}

?>