<?php

/**
 * Created by PhpStorm.
 * User: marjani
 * Date: 2/27/2016
 * Time: 11:02 AM
 */
class contactusModelDb
{

    static function insert($fields)
    {

        $conn = dbConn::getConnection();
        $sql = "
                    INSERT INTO contactus(
                    `subject`,
                    `email`,
                    `comment`,
                    `status`,
                    `date`
                    )
                    VALUES(
                    '" . $fields['subject']  . "',
                    '" . $fields['email']  . "',
                    '" . $fields['comment']  . "',
                    '0',
                    NOW()
                    )";


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
        $result['export']['insert_id']=$conn->lastInsertId();
        $result['result'] = 1;
        return $result;
    }






}