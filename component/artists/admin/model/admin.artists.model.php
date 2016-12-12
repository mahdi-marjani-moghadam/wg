<?php
/**
 * Created by PhpStorm.
 * User: marjani
 * Date: 3/6/2015
 * Time: 10:35 AM
 */

//include_once(ROOT_DIR . "/common/validators.php");

class adminArtistsModel extends looeic
{
    public $fields;  // other xrecord fields
    public $list;  // other record fields
    private $result;
    public $recordsCount;
    protected $TABLE_NAME = 'artists';
    //private $requiredFields;
    //protected $TABLE_NAME = 'artists';

    /**
     * adminRegisterModel constructor.
     */


    /**
     * @param $field
     * @return mixed
     * @author malekloo
     * @date 3/6/2015
     * @version 01.01.01
     */
    public function __get($field)
    {
        /*if($field == 'result')
        {
            return $this->result;
        } else if($field == 'fields')
        {
            return $this->fields;
        } else if($field == 'list')
        {
            return $this->list;
        } else
        {
            return $this->fields[$field];
        }*/

    }


    /**
     * add artists us
     *
     * @return mixed
     * @author malekloo
     * @date 3/6/2015
     * @version 01.01.01
     */
    public function addArtists()
    {

        foreach($this->requiredFields as $field => $val)
        {
            $requiredList[$field] = $this->fields[$field];
        }

        $result = $this->setFields($requiredList);
        if($result['result'] == -1)
        {
            return $result;
        }

        include_once(dirname(__FILE__) . "/admin.artists.model.db.php");

        // echo "<pre>";
        // print_r($this->fields);
        // die();

        $result = adminArtistsModelDb::insert($this->fields);
        if($result['result'] != 1)
        {
            return $result;
        }

        $this->fields['Artists_id'] = $result['export']['insert_id'];

        $result = adminArtistsModelDb::insertToPhones($this->fields, $this->fields['Artists_id']);
        if($result['result'] != 1)
        {
            return $result;
        }

        $result = adminArtistsModelDb::insertToEmails($this->fields, $this->fields['Artists_id']);
        if($result['result'] != 1)
        {
            return $result;
        }

        $result = adminArtistsModelDb::insertToAddresses($this->fields, $this->fields['Artists_id']);
        if($result['result'] != 1)
        {
            return $result;
        }

        $result = adminArtistsModelDb::insertToWebsites($this->fields, $this->fields['Artists_id']);
        if($result['result'] != 1)
        {
            return $result;
        }

        return $result;
    }


    /**
     * edit artists by Artists_id
     *
     * @return mixed
     * @author malekloo
     * @date 3/06/2015
     * @version 01.01.01
     */
    public function edit()
    {
        foreach($this->requiredFields as $field => $val)
        {
            $requiredList[$field] = $this->fields[$field];
        }
        $result = $this->setFields($requiredList);
        print_r_debug($requiredList);

        if($result['result'] == -1)
        {
            return $result;
        }

        include_once(dirname(__FILE__) . "/admin.artists.model.db.php");
        // companies
        $result = adminArtistsModelDb::update($this->fields);
        if($result['result'] != 1)
        {
            return $result;
        }
        // phones
        $result = adminArtistsModelDb::deletePhones($this->fields['Artists_id']);
        if($result['result'] != 1)
        {
            return $result;
        }
        $result = adminArtistsModelDb::insertToPhones($this->fields,$this->fields['Artists_id']);
        if($result['result'] != 1)
        {
            return $result;
        }
        // emails
        $result = adminArtistsModelDb::deleteEmails($this->fields['Artists_id']);
        if($result['result'] != 1)
        {
            return $result;
        }
        $result = adminArtistsModelDb::insertToEmails($this->fields,$this->fields['Artists_id']);
        if($result['result'] != 1)
        {
            return $result;
        }
        // addresses
        $result = adminArtistsModelDb::deleteAddresses($this->fields['Artists_id']);
        if($result['result'] != 1)
        {
            return $result;
        }
        $result = adminArtistsModelDb::insertToAddresses($this->fields,$this->fields['Artists_id']);
        if($result['result'] != 1)
        {
            return $result;
        }
        // websites
        $result = adminArtistsModelDb::deleteWebsites($this->fields['Artists_id']);
        if($result['result'] != 1)
        {
            return $result;
        }
        $result = adminArtistsModelDb::insertToWebsites($this->fields,$this->fields['Artists_id']);
        if($result['result'] != 1)
        {
            return $result;
        }

        return $result;
    }


    /**
     * get all artists
     *
     * @param $fields
     * @return mixed
     * @author malekloo
     * @date 2/24/2015
     * @version 01.01.01
     */
    public function getArtists($fields)
    {
        include_once(dirname(__FILE__) . "/admin.artists.model.db.php");


        $result = adminArtistsModelDb::getArtists($fields);

        if($result['result'] != 1)
        {
            return $result;
        }
        $this->list = $result['export']['list'];
        $this->recordsCount = $result['export']['recordsCount'];

        return $result;
    }

    /**
     * get getArtistsById
     *
     * @param $id
     * @return mixed
     */
    public function getArtistsById($id)
    {
        include_once(dirname(__FILE__) . "/admin.artists.model.db.php");

        $result = adminArtistsModelDb::getArtistsById($id);

        if($result['result'] != 1)
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

        $this->fields = $result['export']['list'];

        return $result;
    }

    /**
     * delete artists by artists_id
     *
     * @return mixed
     * @author mahmoud malekloo <mahmoud.malekloo@gmail.com>
     * @date 2/24/2015
     * @version 01.01.01
     */
    public function delete()
    {
        include_once(dirname(__FILE__) . "/admin.artists.model.db.php");
        $result = adminArtistsModelDb::delete($this->fields['Artists_id']);

        return $result;
    }



}
