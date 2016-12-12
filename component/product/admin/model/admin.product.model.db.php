<?php

/**
 * Created by PhpStorm.
 * User: malekloo
 * Date: 2/27/2016
 * Time: 11:02 AM
 */
class adminProductModelDb
{
    static function insert($fields)
    {
        $fields['status']='1';
        $category_st='';
        if(count($fields['category_id'])>0)
        {
            $category_st=implode(',',$fields['category_id']);
            $category_st=','.$category_st.',';
        }

        include_once ROOT_DIR.'component/company/admin/model/admin.company.model.db.php';

        $company = adminCompanyModelDb::getCompanyById($fields['company_id']);

        $cityId = $company['export']['list']['city_id'];

        $category_rs= self::arrayToTag($fields['category_id']);
        $fields['category_list'] =$category_rs['export']['list'];
        $conn = dbConn::getConnection();
        $sql = "
                    INSERT INTO company_products(
                    `company_id`,
                    `category_id`,
                    `city_id`,
                    `title`,
                    `brif_description`,
                    `description`,
                    `meta_keyword`,
                    `image`,
                    `date`,
                    `status`,
                    `priority`,
                    )
                    VALUES(
                    '" . $fields['company_id']  . "',
                    '" . $fields['category_list']  . "',
                    '" . $cityId  . "',
                    '" . $fields['title']  . "',
                    '" . $fields['brif_description']  . "',
                    '" . $fields['description']  . "',
                    '" . $fields['meta_keyword']  . "',
                    '" . $fields['image']  . "',
                     NOW(),
                    '" . $fields['status']  . "',
                    '" . $fields['priority']  . "'
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


    /**
     * edit product by Product_id
     * @param $fields
     * @return mixed
     * @author malekloo
     * @date 3/16/2015
     * @version 01.01.01
     */
    static function update($fields)
    {
        $conn = dbConn::getConnection();

        $temp = self::arrayToTag($fields['category_id']);
        $fields['category_id'] =    $temp ['export']['list'];

        $sql = "UPDATE company_products SET ";
        foreach($fields as $fieldName =>$val)
        {
            //echo $fieldName.'='.$val;
            $sql=$sql."`".$fieldName."` = '".$val . "',";
        }

        $sql=substr($sql,0,-1);
        $sql=$sql." WHERE Company_products_id = '" . $fields['Company_products_id'] . "'";

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
     * edit product by Product_id
     * @param $fields
     * @return mixed
     * @author malekloo
     * @date 3/16/2015
     * @version 01.01.01
     */
    static function updateCompanyProductsCity($cityId,$companyId)
    {
        $conn = dbConn::getConnection();
        $sql = "UPDATE company_products SET `city_id` = '$cityId' WHERE company_id = '$companyId'";

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
     * Get product by company_id
     * @param $fields
     * @return mixed
     * @author malekloo
     * @date 3/28/2016
     * @version 01.01.01
     */
    public function getProduct($fields='')
    {

        $conn = dbConn::getConnection();

        include_once(ROOT_DIR."/model/db.inc.class.php");

        $condition= DataBase::filterBuilder($fields);

        if($condition['list']['WHERE']!='')
        {
            $append_sql=' AND ';

        }


         $sql = "SELECT SQL_CALC_FOUND_ROWS
                 *
    		     FROM 	artists_products WHERE artists_id='{$fields['choose']['artists_id']}' ".$append_sql.$condition['list']['filter'].$condition['list']['order'].$condition['list']['limit'];


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
        $row_count = $stmTp->fetch();

        $result['export']['recordsCount']= $row_count['recCount'];

        while ($row = $stmt->fetch())
        {

            $list[$row['Artists_products_id']]= $row;
        }

        $result['result'] = 1;
        $result['export']['list'] = $list;

        return $result;

    }

    static function getProductById($id)
    {
        //global $lang;
        $conn = dbConn::getConnection();
        $sql = "SELECT
                    *
                FROM
                    artists_products
                WHERE
                    Artists_products_id= '$id'";

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
        $temp= adminProductModelDb::tagToArray($row['category_id']);
        $row['category_id']=$temp['export']['list'];
        $result['result'] = 1;
        $result['export']['list'] = $row;

        return $result;

    }

    static function getProductByCompanyId($id)
    {
        $conn = dbConn::getConnection();
        $sql = "SELECT
                *
                FROM
                    company_products
                WHERE
                    company_id ='$id' ";

        $stmt = $conn->prepare($sql);
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

        while ($row = $stmt->fetch())
        {
            $list[$row['Company_products_id']]= $row;
        }

        $result['result'] = 1;
        $result['export']['list'] = $list;

        return $result;

    }

    static function delete($id)
    {

        $conn = dbConn::getConnection();

        $sql = "
                DELETE FROM company_products
                    WHERE Company_products_id = '" . $id . "'
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

    static function arrayToTag($input)
    {
        $export='';
        if(count($input)>0)
        {
            $export=implode(',',$input);
            $export=','.$export.',';
        }
        $result ['export']['list']=$export;
        $result['result']='1';
        return $result;
    }

    static function tagToArray($input)
    {
        $export=explode(',',$input);
        $export=array_filter($export,'strlen');
        $result ['export']['list']=$export;
        $result['result']='1';
        return $result;
    }


}
