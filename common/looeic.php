<?php

/**
 * Created by PhpStorm.
 * User: malek
 * Date: 2/20/2016
 * Time: 4:33 AM
 */
include_once ROOT_DIR.'common/GUMP-master/gump.class.php';
class looeic
{
    protected $fields;
    protected $TABLE_FIELD;
    protected $rules;
    private $extendClass;

    private $PRI_KEY ='';
    protected  $TABLE_NAME;
    private $err;
    private $list;
    private $sql;
    private $relation;
    private $where;
    private $selectFields;
    private $select;
    private $finalQuery='0';



    public function hasOne($model,$key,$component)
    {
        return $this->hasAll($model,$key,$component)->first();
    }
    public function hasMany($model,$key,$component)
    {
        return $this->hasAll($model,$key,$component);
    }
    private function hasAll($model,$key,$component)
    {

        $this->appendFields();

        if($component!='')
        {
            $componenetAdress = ROOT_DIR."component/".$component."/".$component.".php";
            include_once $componenetAdress;
        }
        $funcName=getBy.'_'.$key;
        $val=$this->fields[$this->PRI_KEY];
        //echo $model;
        //echo '::'.$funcName.'('.$val.')';
        //print_r_debug($this->sql);
        $this->relation[$model]= $model::$funcName($val);

        return $this->relation[$model];

    }

    public function hasLeft($model,$key,$component)
    {

        $this->appendFields('*',1);
        //echo '****************';

        $conn = dbConn::getConnection();
        $stmt = $conn->prepare($this->sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        if (!$stmt)
        {
            $result['result'] = -1;
            $result['Number'] = 1;
            $result['msg'] = $conn->errorInfo();
            return $result;
        }
        $row = $stmt->fetch();

        //print_r_debug($row['ALL_IDS']);
        if($component!='')
        {
            $componenetAdress = ROOT_DIR."component/".$component."/".$component.".php";
            include_once $componenetAdress;
        }
        $funcName=getBy.'_'.$key;
        $val=explode(" ",$row['ALL_IDS']);

        $this->relation[$model]= $model::$funcName($val);

        return $this->relation[$model];

    }

    public function __construct($fields='')
    {
        $input=func_get_args();
        if($input[1]!='') {

            $this->getTableName($input[1]);
        }
        $this->getFieldsName();
        if($fields!='')
        {
            $this->setFields($fields);
        }
    }

    function  __callStatic($name, $arguments)
    {
        if(strpos($name,'getBy')===0)
        {
            return self::getby($name,$arguments);
        }
    }
    function  __call($name, $arguments)
    {
        // setExtendClass
        if($name=='setExtendClass')
        {
            $this->extendClass=$arguments[0];
        }
        if($name=='findModel')
        {
            return $this->findModel($arguments[0]);
        }
        if($name=='getbyModel')
        {
            return $this->getbyModel($arguments[0],$arguments[1]);
        }

        // TODO: Implement __call() method.
        //print_r($name);
        //print_r($arguments);
        //echo '__call';
    }
    function  validator($rules ='',$fields= '')
    {
        if(($rules) == '')
        {
            $rules=$this->rules;
        }
        if(($fields) == '')
        {
            $fields=$this->fields;
        }

        $validator = new GUMP();


        $valid = $validator->validate($fields,$rules);

        $this->err=$validator->get_errors_array();
        $result=$this->err;
        if(count($this->err))
        {
            $result['result']='-1';
        }else
        {
            $result['result']='1';

        }

        return $result;

    }

    function  getErr()
    {
        return $this->err;
    }

    function orderBy($fields,$sortby='ASC')
    {
        $this->sql.=" order by $fields $sortby";

        return $this;
    }
    static function query($sql)
    {
        $className=get_called_class();
        $obj= new $className('',get_called_class());
        $obj->getFieldsName();

        $obj->sql.=$sql;
        $obj->finalQuery=1;

        return $obj;

    }

    /////////////
    function first()
    {
        if(strlen($this->sql)<1)
        {
            $result['result'] = -1;
            $result['Number'] = 1;
            $result['msg'] = 'not found';
            return $result;
        }

        $this->appendFields();

        $conn = dbConn::getConnection();

        $stmt = $conn->prepare($this->sql);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        if (!$stmt)
        {
            $result['result'] = -1;
            $result['Number'] = 1;
            $result['msg'] = $conn->errorInfo();
            return $result;
        }
        $row = $stmt->fetch();
        //$extendClass=$this->extendClass;
        $temp_object=$this->findModel($row[$this->PRI_KEY]);
        //$temp_object=$this->extendClass::find($conn->lastInsertId());
        return $temp_object;

    }
    private function get_object_or_list($object=1)
    {

        $conn = dbConn::getConnection();

        $stmt = $conn->prepare($this->sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        if (!$stmt)
        {
            $result['result'] = -1;
            $result['Number'] = 1;
            $result['msg'] = $conn->errorInfo();
            return $result;
        }
        $result['export']['recordsCount']= $stmt->rowCount();
        if ($object==1)
        {
            while ($row = $stmt->fetch())
            {
                $temp_object=$this->findModel($row[$this->PRI_KEY]);
                $result['export']['list'][]=clone ($temp_object);
            }

        }else
        {
            while ($row = $stmt->fetch())
            {
                $result['export']['list'][]=$row;
            }
        }

        $result['result'] = 1;
        return $result;

    }

    function get()
    {

        if(strlen($this->sql)<1)
        {
            $result['result'] = -1;
            $result['Number'] = 1;
            $result['msg'] = 'not found';
            return $result;
        }

        $this->appendFields();


        return $this->get_object_or_list(1);
    }

    function getList($fields)
    {
        if(strlen($this->sql)<1)
        {
            $result['result'] = -1;
            $result['Number'] = 1;
            $result['msg'] = 'not found';
            return $result;
        }
        $this->appendFields();

        return $this->get_object_or_list(0);

    }

    function appendFields($field='*',$concat=0)
    {

        if($this->finalQuery=='0')
        {
            if($concat==1)
            {
                $this->sql=" SELECT Group_Concat( ". $this->PRI_KEY.") AS ALL_IDS ". $this->sql;

            }else
            {

                $this->selectFields=" * ";
                $this->sql=" SELECT ". $this->selectFields.$this->sql;
            }
        }


    }
    public static function getAll()
    {

        $className=get_called_class();
        $obj= new $className('',get_called_class());
        $obj->getFieldsName();
        $appendSql='';

        $obj->getFieldsName();
        $sql = " FROM ".$obj->TABLE_NAME ." ";
        $obj->sql=$sql;
        return $obj;

    }
    private function getbyModel($name, $arguments)
    {

        $name=substr($name,6);

        $a=preg_match_all('/(?J)(?<or>_or_)|(?<and>_and_)/',$name,$matches);

        $ready = str_replace(array("_or_","_and_"),'_or_', $name);
        $filter_fields=explode('_or_',$ready);



        $appendSql='';
        foreach($filter_fields as $key=> $fields)
        {
            $operator=' = ' ;
            if(strpos($fields,'not_')===0)
            {

                $fields=substr($fields,4);
                $operator='<>';

            }
            $arrayInput='';
            if(is_array($arguments[$key]))
            {
                if($operator=='<>')
                {
                    $operator=' NOT ';
                }else
                {
                    $operator='';

                }


                $arrayInput=implode(",",$arguments[$key]);
                $appendSql.= "`".$fields."` $operator in (".$arrayInput.") ".str_replace('_',' ',$matches[0][$key])." ";

            }else
            {
                $appendSql.= "`".$fields."` ".$operator." '".$arguments[$key]."' ".str_replace('_',' ',$matches[0][$key])." ";

            }

        }
        $this->getFieldsName();
        $sql = " FROM ".$this->TABLE_NAME ." WHERE ".$appendSql." ";
        $this->sql=$sql;

        return $this;

    }
    static private function getby($name, $arguments)
    {
        $className=get_called_class();
        $obj= new $className('',get_called_class());
        $obj->getFieldsName();

        $obj->getbyModel($name, $arguments);

        return $obj;
    }

    function getFieldsName()
    {

        if($this->TABLE_NAME=='')
        {
            $this->TABLE_NAME = $this->getTableName(get_called_class());
        }

        if(!is_array($this->TABLE_FIELD))
        {
            $conn = dbConn::getConnection();
            $sql = "SHOW COLUMNS FROM ".$this->TABLE_NAME." ";
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

            while ($row = $stmt->fetch())
            {
                //$stmt->rowCount();
                $this->TABLE_FIELD[$row['Field']]='';
                if($row['Key']=='PRI')
                {
                    $this->PRI_KEY=$row['Field'];
                }
            }
        }


    }

    private function  checkMysqlValue($value)
    {

        if (strpos($value, 'callMysql') === 0) {
            $return = trim(substr($value, 9));
            $return = trim(substr($return, 1, (strlen($return) - 2)));
            return $return;
        }else
        {
            return "'".$value."'";
        }

    }

    function  __set($name, $value)
    {
        $value=trim($value);
        $this->getFieldsName();

        if(!array_key_exists($name,$this->TABLE_FIELD))
        {
            return ;
        }

        $this->fields[$name]=$value;
    }

    public function __get($name)
    {
        $this->getFieldsName();
        if($name=='fields')
        {
            return $this->fields;
        }else if(array_key_exists($name,$this->fields))
        {
            return $this->fields[$name];
        }else if(is_callable(array($this, $name)))
        {
            return $this->$name();

        }
    }

    public function setFields($fields)
    {
        foreach($this->TABLE_FIELD as $field_name =>$val)
        {
            // print_r_debug($this->PRI_KEY);
            if($field_name==$this->PRI_KEY)
            {
                continue;
            }

            if(array_key_exists($field_name,$fields))
            {
                $this->fields[$field_name]=$fields[$field_name];
            }
        }
        $result['result']=1;
        //print_r_debug($fields);
        return $result;
    }

    public function getByFilter($fields='',$query)
    {
        //$obj->TABLE_NAME=get_called_class();

        $this->getTableName(get_called_class());

        $conn = dbConn::getConnection();

        include_once(ROOT_DIR."/model/db.inc.class.php");

        $condition= DataBase::filterBuilder($fields);
        if($query!='')
        {

            $sql = "SELECT SQL_CALC_FOUND_ROWS

                `t1`.* FROM( $query ) as t1 "
                .$condition['list']['useWhere'].
                $condition['list']['WHERE'].$condition['list']['filter'].
                $condition['list']['order'].$condition['list']['limit'];

        }else
        {

            $sql = "SELECT SQL_CALC_FOUND_ROWS
                 *
    		     FROM 	".$this->TABLE_NAME." ".$condition['list']['useWhere'].
                $condition['list']['WHERE'].$condition['list']['filter'].
                $condition['list']['order'].$condition['list']['limit'];
        }

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        //print_r_debug($stmt);
        if (!$stmt)
        {
            $result['result'] = -1;
            $result['no'] = 1;
            $result['msg'] = $conn->errorInfo();
            return $result;
        }

        $sql=" SELECT FOUND_ROWS() as recCount ";

        $stmTp = $conn->prepare($sql);
        $stmTp->setFetchMode(PDO::FETCH_ASSOC);
        $stmTp->execute();
        $rowP = $stmTp->fetch();

        $result['export']['recordsCount']= $rowP['recCount'];


        while ($row = $stmt->fetch())
        {
            $list[]= $row;
        }
        $result['result'] = 1;
        $result['export']['list'] = $list;

        return $result;

    }


    private function getTableName ($className)
    {


        if($this->TABLE_NAME!='')
        {
            return $this->TABLE_NAME;
        }
        $this->extendClass=$className;
        if(strpos($className,'admin')==0 and strpos($className,'Model'))
        {

            $return= substr($className,5,strlen($className)-10);
            $return = strtolower($return);
            $this->TABLE_NAME=$return;
            return $return;

            //echo  strpos($className,'admin');
            //echo  strpos($className,'Model');
        }else
        {
            $this->TABLE_NAME=$className;
            return $className;
        }


    }
    public function save ()
    {
        if($this->fields[$this->PRI_KEY]=='')
        {
            $this->insert();
        }else
        {
            $this->updateModel();
        }
        $result['result']=1;
        return $result;
    }

    private function insert($fields='')
    {
        $sql_key='';
        $sql_val='';
        foreach ($this->fields as $key =>$value)
        {
            $sql_key.="`".$key."`,";
            $sql_val.= $this->checkMysqlValue($value).',' ;
        }
        $sql_key=substr($sql_key,0,-1);
        $sql_val=substr($sql_val,0,-1);

        $conn = dbConn::getConnection();
        $sql = "
                    INSERT INTO ".$this->TABLE_NAME."( ".$sql_key ." ) VALUES ( ".$sql_val." ) ";
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

        $this->fields[$this->PRI_KEY] =$conn->lastInsertId();
        $result['export']['insert_id']=$conn->lastInsertId();
        $result['result'] = 1;
        $extendClass=$this->extendClass;
        $key=$this->PRI_KEY;
        $conn->lastInsertId();

        //$temp_object=$extendClass::find($conn->lastInsertId());

        //$temp_object=$this->extendClass::find($conn->lastInsertId());
        //$this->fields=$temp_object->fields ;
        return $result;
    }
    public static function update($fields,$where)
    {

        $input=func_get_args();

        $className=get_called_class();
        $tableName=$className;

        $obj= new $className('',$tableName);
        $obj->getFieldsName();


        $sql_key='';
        $sql_val='';
        $sql_key_val='';
        foreach ($fields as $key =>$value)
        {
            if($key==$obj->PRI_KEY)
            {
                continue;
            }
            if(array_key_exists($key,$obj->TABLE_FIELD))
            {
                $sql_key ="`".$key."` ";

                $sql_val=$obj->checkMysqlValue($value);

                $sql_key_val .= $sql_key.' = '.$sql_val.',';
            }

        }

        $sql_key_val=substr($sql_key_val,0,-1);

        $conn = dbConn::getConnection();
        $sql = " UPDATE ".$obj->TABLE_NAME." SET ".$sql_key_val." 
         WHERE ".$where." ";

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
    private function updateModel($fields)
    {
        $sql_key='';
        $sql_val='';
        $sql_key_val='';
        foreach ($this->fields as $key =>$value)
        {
            if($key==$this->PRI_KEY)
            {
                continue;
            }
            $sql_key ="`".$key."` ";
            $sql_val=$this->checkMysqlValue($value);
            $sql_key_val .= $sql_key.' = '.$sql_val.',';
        }
        //$sql_key_val .= $sql_key.' = '.$sql_val.' ,';

        $sql_key_val=substr($sql_key_val,0,-1);

        $conn = dbConn::getConnection();
        $sql = " UPDATE ".$this->TABLE_NAME." SET ".$sql_key_val." 
         WHERE ".$this->PRI_KEY." = '" . $this->fields[$this->PRI_KEY] . "' ";

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


    public function delete()
    {
        if($this->fields[$this->PRI_KEY]=='')
        {
            $result['result'] = -1;
            $result['Number'] = 1;
            $result['msg'] = 'not found';
            return $result;        }
        $conn = dbConn::getConnection();
        $sql = " DELETE FROM ".$this->TABLE_NAME."  WHERE ".$this->PRI_KEY." = '" . $this->fields[$this->PRI_KEY] . "' ";

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

    private function findModel($id)
    {
        $conn = dbConn::getConnection();

        $sql = "SELECT
                *
            FROM ".
            $this->TABLE_NAME
            ." WHERE ".
            $this->PRI_KEY ." = '$id' ";


        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        //print_r_debug($stmt);
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
            $result['no'] = 1;
            $result['msg'] = 'This Record was Not Found';
            return $result;
        }
        $row = $stmt->fetch();

        //print_r_debug($row);
        $result['result'] = 1;
        $result['list'] = $row;
        $this->fields=$row;
        //$this->setFields($row);
        // print_r_debug($this);

        return $this;
    }
    static function find($id)
    {
        $input=func_get_args();

        $className=get_called_class();
        $tableName=$className;

        $obj= new $className('',$tableName);
        $obj->getFieldsName();

        $obj=$obj->findModel($id);

        return $obj;

    }

    static function create($fields)
    {
        $className=get_called_class();
        $obj= new $className($fields,$className);
        //print_r_debug($obj);
        $obj->save();
        return $obj;
    }
}
class model extends looeic
{
    protected $TABLE_NAME;
    protected $fields;
    protected $rules;
    protected static $obj;
    public function __construct($table,$fields='',$rules='')
    {

        $this->TABLE_NAME=$table;
        $this->setExtendClass('model');
        if (is_array($fields))
        {
            $this->fields=$fields;
        }
        if (is_array($rules))
        {
            $this->rules=$rules;
        }
        parent::__construct('',$table);
        $obj=$this;
    }

    static function find($table,$id)
    {
        $obj= new model($table);
        return $obj->findModel($id);
    }


    function  __callStatic($name, $arguments)
    {
        if(strpos($name,'getBy')===0)
        {
            return self::getby($name,$arguments);
        }
        return parent::__callStatic($name, $arguments);
    }

    static function getby($name, $arguments)
    {

        $table=$arguments[0];
        $obj= new model($table);
        array_shift($arguments);
        $obj->getbyModel($name, $arguments);
        return $obj;

    }




}