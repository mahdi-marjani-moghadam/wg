<?php

/**
 * Created by PhpStorm.
 * User: malek
 * Date: 2/20/2016
 * Time: 4:33 AM
 */
class adminContactusModelDb
{

    

    static function update($fields)
    {

        $conn = dbConn::getConnection();

        $sql = "
                UPDATE contactus
                  SET
                    `title`             =   '" . $fields['title'] . "',
                    `brif_description`  =   '" . $fields['brif_description'] . "',
                    `description`       =   '" . $fields['description'] . "',
                    `meta_keyword`      =   '" . $fields['meta_keyword'] . "',
                    `meta_description`  =   '" . $fields['meta_description'] . "',
                    `image`             =   '" . $fields['image'] . "'
                    WHERE Contactus_id = '" . $fields['Contactus_id'] . "'
                    ";

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

    /**
     * @param $fields
     * @return mixed
     */
    static function delete($fields)
    {

        $conn = dbConn::getConnection();

       $sql = "
                DELETE FROM contactus
                    WHERE Contact_id = '" . $fields['Contact_id'] . "'
                    ";
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

    static function getContactusById($id)
    {
        //global $lang;
        $conn = dbConn::getConnection();
        $sql = "SELECT
                    *
                FROM
                    contactus
                WHERE
                    Contact_id= '$id'";

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
            $result['no'] = 1;
            $result['msg'] = 'This Record was Not Found';
            return $result;
        }

        $row = $stmt->fetch();

        $result['result'] = 1;
        $result['list'] = $row;

        return $result;

    }

    public function getContactus($fields='')
    {

        $conn = dbConn::getConnection();

        include_once(ROOT_DIR."/model/db.inc.class.php");

        $condition= DataBase::filterBuilder($fields);
        $sql = "SELECT SQL_CALC_FOUND_ROWS
                 *
    		     FROM 	contactus ".$condition['list']['WHERE'].$condition['list']['filter'].$condition['list']['order'].$condition['list']['limit'];

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

        $sql=" SELECT FOUND_ROWS() as recCount ";

        $stmTp = $conn->prepare($sql);
        $stmTp->setFetchMode(PDO::FETCH_ASSOC);
        $stmTp->execute();
        $rowP = $stmTp->fetch();

        $result['export']['recordsCount']= $rowP['recCount'];

        while ($row = $stmt->fetch())
        {
            $list[$row['Contact_id']]= $row;
        }
        $result['result'] = 1;
        $result['export']['list'] = $list;
        return $result;

    }

    static public function getContactusEasy()
    {
        //global $lang;

        $conn = dbConn::getConnection();
        $sql = "SELECT
                    *
                FROM
                    contactus
                   ORDER BY 'date' DESC ";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if (!$stmt)
        {
            $result['result'] = -1;
            $result['no'] = 1;
            $result['msg'] = $conn->errorInfo();
            return $result;
        }

        $list = $stmt->fetchAll();
        $result['result'] = 1;
        $result['export']['list'] = $list;

        return $result;

    }



}