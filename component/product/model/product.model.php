<?php
/**
 * Created by PhpStorm.
 * User: malek
 * Date: 2/20/2016
 * Time: 4:24 AM.
 */
include_once ROOT_DIR.'/common/validators.php';
class productModel extends looeic
{
    protected $TABLE_NAME = 'artists_products';
    private $TableName;
    //private $fields;  // other record fields
    private $list;  // other record fields
    private $recordsCount;  // other record fields
    private $pagination;  // other record fields

    private $result;

    /**
     * articleModel constructor.
     */
    public function __constructs()
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
     *
     * @return mixed
     */
    public function __gets($field)
    {
        if ($field == 'result') {
            return $this->result;
        } elseif ($field == 'fields') {
            return $this->fields;
        } elseif ($field == 'list') {
            return $this->list;
        } elseif ($field == 'recordsCount') {
            return $this->recordsCount;
        } elseif ($field == 'pagination') {
            return $this->pagination;
        } else {
            return $this->fields[$field];
        }
    }

    /**
     * @param $input
     *
     * @return int
     */
    public function setFieldss($input)
    {
        foreach ($input as $field => $val) {
            $funcName = '__set'.ucfirst($field);
            if (method_exists($this, $funcName)) {
                $result = $this->$funcName($val);
                if ($result['result']) {
                    $this->fields[$field] = $val;
                } else {
                    return $result;
                }
            }
        }
        $result = 1;

        return $result;
    }

    /**
     * @param $input
     *
     * @return mixed
     */
    private function __setTitle($input)
    {
        if (!Validator::required($input)) {
            $result['result'] = -1;
            $result['msg'] = 'pleas enter title';
        } else {
            $result['result'] = 1;
        }

        return $result;
    }
    /**
     * get Product By Company Id.
     *
     * @param $id
     *
     * @return mixed
     */
    public function getProductByCompanyId($id)
    {
        include_once dirname(__FILE__).'/product.model.db.php';

        $result = productModelDb::getProductByCompanyId($id);

        if ($result['result'] != 1) {
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

        $this->list = $result['list'];
        $this->list = $result['export']['list'];
        $this->recordsCount = $result['export']['recordsCount'];

        return $result;
    }
    public function getProductByArtistsId($id,$fields)
    {
        include_once dirname(__FILE__).'/product.model.db.php';

        $result = productModelDb::getProductByArtistsId($id,$fields);

        if ($result['result'] != 1) {
            return $result;
        }
        $this->recordsCount = $result['export']['recordsCount'];

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

        $page = $this->pagination();

        if($page['result'] == 1)
        {
            $result['pagination'] = $page['export'];
        }

        $this->list = $result['list'];
        $this->list = $result['export']['list'];
        $this->recordsCount = $result['export']['recordsCount'];

        return $result;
    }

    /**
     * get Product By Id.
     *
     * @param $id
     *
     * @return mixed
     */
    public function getProductById($id)
    {
        include_once dirname(__FILE__).'/product.model.db.php';

        $result = productModelDb::getProductById($id);

        if ($result['result'] != 1) {
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

        $this->fields = $result['list'];

        return $result;
    }

     /**
      * @param $fields
      *
      * @return mixed
      */
     /**
      * @param $fields
      *
      * @return mixed
      */
     public function getRelatedProducts($id, $companyId = null)
     {
         include_once dirname(__FILE__).'/product.model.db.php';
         $result = productModelDb::getRelatedProducts($id, $companyId);
         if ($result['result'] != 1) {
             return $result;
         }
         $this->list = $result['export']['list'];

         return $result;
     }
    /**
     *
     */
    public function getProductByCategoryId($fields)
    {
        include_once dirname(__FILE__).'/product.model.db.php';

        $result = productModelDb::getProductByCategoryId($fields);

        if ($result['result'] != 1) {
            return $result;
        }
        $this->list = $result['export']['list'];
        $this->recordsCount = $result['export']['recordsCount'];

        $resultPage = $this->pagination();

        $this->pagination = $resultPage['export']['list'];

        return $result;
    }

    /**
     * @param $fields
     *
     * @return mixed
     */
    public function getProduct($fields)
    {
        include_once dirname(__FILE__).'/product.model.db.php';

        $result = productModelDb::getProduct($fields);

        if ($result['result'] != 1) {
            return $result;
        }
        $this->list = $result['export']['list'];
        $this->recordsCount = $result['export']['recordsCount'];

        $resultPage = $this->pagination();

        $this->pagination = $resultPage['export']['list'];

        return $result;
    }

    /**
     * get article by category
     *  // example catString : 1,2,3,5,88
     *  // example catArray : array('1'=>'3','2'=>'2','3'=>'23').
     *
     * @param $fields
     *
     * @author marjani
     * @date 2/29/2016
     *
     * @version 01.01.01
     */
    public function getArticleByCategoryId($fields)
    {
        if (!is_array($fields)) {
            $fields = handleData($fields);
            $fields = explode(',', $fields);
        }
        $catString = '';
        foreach ($fields as $k => $catid) {
            if (is_numeric($catid)) {
                $catString .= ",'".$catid."'";
            }
        }
        $catString = substr($catString, 1);

        include_once dirname(__FILE__).'/article.model.db.php';
        $result = articleModelDb::getArticleByCategoryId($catString);

        $this->list = $result['export']['list'];

        return $result;
    }

    /**
     * @return mixed
     */

    private function pagination()
    {


        $pageCount = ceil($this->recordsCount / PAGE_SIZE);
        $pagination = array();
        $temp = 1;

        $url_main = substr($_SERVER['REQUEST_URI'], strlen(SUB_FOLDER) + 1);
        $url_main = urldecode($url_main);

        $PARAM = explode('/', $url_main);
        $PARAM = array_filter($PARAM, 'strlen');

        if (array_search('page', $PARAM)) {
            $index_pageSize = array_search('page', $PARAM);

            //$page=$PARAM[$index_pageSize+1];
            unset($PARAM[$index_pageSize]);
            unset($PARAM[$index_pageSize + 1]);

            $PARAM = implode('/', $PARAM);
            $PARAM = explode('/', $PARAM);
            $PARAM = array_filter($PARAM, 'strlen');
        }

        for ($i = 1;$i <= $pageCount;++$i) {
            $pagination[] = $PARAM[0].'/'.$PARAM[1].'/page/'.$temp;
            $temp = $temp + 1;
        }



        $result['result'] = 1;
        $result['export']['list'] = $pagination;

        $url_main = substr($_SERVER['REQUEST_URI'], strlen(SUB_FOLDER) + 1);
        $url_main = urldecode($url_main);
        $PARAM = explode('/', $url_main);
        $PARAM = array_filter($PARAM, 'strlen');
        if (array_search('page', $PARAM)) {
            $index_pageSize = array_search('page', $PARAM);
            $page = $PARAM[$index_pageSize + 1];
        }
        else
        {
            $page = 1;
        }
        $result['export']['current'] = $page;

        return $result;
    }

    public function pushRate($fields)
    {
        if ($fields['val'] == '')
        {
            $result['result'] = -1;
            $result['msg'] = 'Val is empty';
            return $result;
        }
        include_once ROOT_DIR . 'component/artists/model/artists.model.php';
        $artists = new artistsModel();
        $result = $artists->getArtistsById($fields['artists_id']);
        if($result['result'] == -1)
        {
            return $result;
        }

        $artistsRate = $result['list']['rate']*$result['list']['rate_count'];
        $artistsRateCount = $result['list']['rate_count']+1;
        $artistsNewRate = ($artistsRate+ $fields['val'])/$artistsRateCount;

        $result = artistsModelDb::pushRateDB($artistsNewRate,$artistsRateCount,$fields['artists_id']);
        if($result['result'] == -1)
        {
            return $result;
        }

        include_once dirname(__FILE__) . '/product.model.db.php';
        $result = $this->getProductById($fields['product_id']);
        if($result['result'] == -1)
        {
            return $result;
        }

        $rate = $result['list']['rate']*$result['list']['rate_count'];
        $rate_count = $result['list']['rate_count']+1;

        $newRate = ($rate+ $fields['val'])/$rate_count;


        $result = productModelDb::pushRateDB($newRate,$rate_count,$fields['product_id']);

        $result['rate'] = $fields['val'];


        return $result;

    }
}