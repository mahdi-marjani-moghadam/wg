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
    private $fields;  // other record fields
    private $list;  // other record fields
    private $recordsCount;  // other record fields
    private $pagination;  // other record fields



    private $result;

    /**
     * newsModel constructor.
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
        else if ($field == 'pagination')
        {
            return $this->pagination;
        }

        else
        {
            return $this->fields[$field];
        }

    }

    /**
     * @param $input
     * @return int
     */
    public function setFields ($input)
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
        $result=1;
        return $result;

    }

    /**
     *
     * @param $input
     * @return mixed
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
     * get news detail
     *
     * @param $id
     * @return mixed
     */
    public function getNewsById($id)
    {
        include_once(dirname(__FILE__)."/news.model.db.php");

        $result=newsModelDb::getNewsById($id);

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
     *
     * @param $fields
     * @return mixed
     */
    public function getNews($fields)
    {
        include_once(dirname(__FILE__)."/news.model.db.php");

        $result=newsModelDb::getNews($fields);

        if($result['result']!=1)
        {
            return $result;
        }
        $this->list=$result['export']['list'];
        $this->recordsCount=$result['export']['recordsCount'];

        if((int)$this->recordsCount = 0)
        {
            $result['msg'] = "رکوردی یافت نشد";
        }

        $resultPage = $this->pagination();


        $this->pagination=$resultPage['export']['list'];


        return $result;
    }

    /**
     * @return mixed
     */
    private function pagination()
    {
        $pageCount = ceil($this->recordsCount/PAGE_SIZE);
        $pagination=array();
        $temp = 1;

        $url_main=substr($_SERVER['REQUEST_URI'],strlen(SUB_FOLDER)+1);
        $url_main=urldecode($url_main);

        $PARAM=explode('/',$url_main);
        $PARAM=array_filter($PARAM,'strlen');

        if(array_search('page',$PARAM))
        {
            $index_pageSize=array_search('page',$PARAM);

            //$page=$PARAM[$index_pageSize+1];
            unset($PARAM[$index_pageSize]);
            unset($PARAM[$index_pageSize+1]);

            $PARAM=implode('/',$PARAM);
            $PARAM=explode('/',$PARAM);
            $PARAM=array_filter($PARAM,'strlen');
        }

        for($i=1;$i<=$pageCount;$i++)
        {

            $pagination[]=$PARAM[0].'/page/'.$temp;
            $temp = $temp + 1;

        }

        $result['result'] = 1;
        $result['export']['list'] = $pagination;
        return $result;


    }


}