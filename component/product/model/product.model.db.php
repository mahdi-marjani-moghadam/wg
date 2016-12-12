<?php

/**
 * Created by PhpStorm.
 * User: malek
 * Date: 2/20/2016
 * Time: 4:33 AM.
 */
class productModelDb
{
    public static function getProductById($id)
    {
        global $lang;
        $conn = dbConn::getConnection();
         $sql = "SELECT
                `artists_products`.*,artists_products.brif_description_$lang as brif_description,artists_products.description_$lang as description,
                `artists`.`artists_name_$lang` as artists_name,`artists_products`.`title_$lang` as title
                FROM
                `artists`
                RIGHT JOIN
                `artists_products`
                ON
                `artists_products`.`artists_id` = `artists`.`Artists_id`
                WHERE
                `artists_products`.`Artists_products_id` = '$id'";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if (!$stmt) {
            $result['result'] = -1;
            $result['Number'] = 1;
            $result['msg'] = $conn->errorInfo();

            return $result;
        }

        if (!$stmt->rowCount()) {
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

    public static function getProductByCompanyId($id)
    {
        $conn = dbConn::getConnection();
        echo $sql = "SELECT
                *
                FROM
                    artists_products
                WHERE
                    artists_id ='$id' ";

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        if (!$stmt) {
            $result['result'] = -1;
            $result['Number'] = 1;
            $result['msg'] = $conn->errorInfo();

            return $result;
        }

        if (!$stmt->rowCount()) {
            $result['result'] = -1;
            $result['no'] = 100;
            $result['msg'] = 'This Record was Not Found';

            return $result;
        }

        $result['export']['recordsCount'] = $stmt->rowCount();

        while ($row = $stmt->fetch()) {
            $list[$row['Company_products_id']] = $row;
        }

        $result['result'] = 1;
        $result['export']['list'] = $list;

        return $result;
    }
    public static function getProductByArtistsId($id,$fields)
    {
        global $lang;
        $conn = dbConn::getConnection();

        include_once(ROOT_DIR."/model/db.inc.class.php");
        $condition = DataBase::filterBuilder($fields);

          $sql = "SELECT  SQL_CALC_FOUND_ROWS
                *,title_$lang as title,brif_description_$lang as brif_description,description_$lang as description
                FROM
                    artists_products
                WHERE
                    artists_id ='$id'   ".$fields['where'].$condition['list']['filter'].$condition['list']['order'].$condition['list']['limit'] ;

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        if (!$stmt) {
            $result['result'] = -1;
            $result['Number'] = 1;
            $result['msg'] = $conn->errorInfo();

            return $result;
        }

        if (!$stmt->rowCount()) {
            $result['result'] = -1;
            $result['no'] = 100;
            $result['msg'] = 'This Record was Not Found';

            return $result;
        }

        $sql = ' SELECT FOUND_ROWS() as recCount ';

        $stmTp = $conn->prepare($sql);
        $stmTp->setFetchMode(PDO::FETCH_ASSOC);
        $stmTp->execute();
        $rowP = $stmTp->fetch();

        $result['export']['recordsCount'] = $rowP['recCount'];

        while ($row = $stmt->fetch()) {
            $list[$row['Artists_products_id']] = $row;
        }

        $result['result'] = 1;
        $result['export']['list'] = $list;
        return $result;
    }

    /**
     * @author vaziry
     */
    public static function getRelatedProducts($id, $companyId = null)
    {
        $product = self::getProductById($id);
        $keywords = explode(',', $product['list']['meta_keyword']);

        $conn = dbConn::getConnection();

        $sql = 'SELECT * FROM company_products WHERE';
        $keyCount = 0;
        foreach ($keywords as $key => $value) {
            if ($value != '') {
                if ($keyCount == 0) {
                    $sql .= " (meta_keyword like '$value' or meta_keyword like '$value,%' or meta_keyword like '%,$value,%' or meta_keyword like '%,$value'";
                } else {
                    $sql .= " or meta_keyword like '$value' or meta_keyword like '$value,%' or meta_keyword like '%,$value,%' or meta_keyword like '%,$value'";
                }
                ++$keyCount;
            }
        }
        if ($keyCount > 0) {
            $sql .= ') AND Company_products_id != '.$id;
            if ($companyId) {
                $sql .= ' AND company_id = '.$companyId;
            }
        } else {
            $sql .= ' 0';
        }

        $sqlLow = $sql." AND (priority != '1' or priority is null) ";
        $sqlHigh = $sql." AND priority = '1' ";

        // $sql .= ') AND Company_id != '.$id;
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        //  if high priority companies are less than 10
        if ($stmt->rowCount() < RELATED_PRODUCT_COUNT) {
            // get limit of low priority companies
            $limit = RELATED_PRODUCT_COUNT - $stmt->rowCount();
            // ---

            // get high priority companies
            $stmt = $conn->prepare($sqlHigh);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $list[$row['Company_products_id']] = $row;
            }
            // ---

            // get low priority companies random
            $stmt = $conn->prepare($sqlLow);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $listTmp[$row['Company_products_id']] = $row;
            }
            if (count($listTmp) >= $limit) {
                $randList = array_rand($listTmp, $limit);
            } else {
                $randList = array_rand($listTmp, count($listTmp));
            }
            if (count($randList) > 1) {
                foreach ($randList as $key => $value) {
                    $list[$value] = $listTmp[$value];
                }
            } elseif (count($randList) == 1) {
                $list[$randList] = $listTmp[$randList];
            }
            // ---
        } else {
            $stmt = $conn->prepare($sqlHigh);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $list[$row['Company_products_id']] = $row;
            }
        }

        $result['result'] = 1;
        $result['export']['list'] = $list;

        return $result;
    }

    public static function getProductByCategoryId($fields = '')
    {
        $conn = dbConn::getConnection();

        include_once ROOT_DIR.'/model/db.inc.class.php';

        $condition = DataBase::filterBuilder($fields);

        $sql = "SELECT SQL_CALC_FOUND_ROWS
                 *
    		     FROM 	company_products where category_id like '%,".$fields['where']['category_id'].",%'".$condition['list']['order'].$condition['list']['limit'];

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        if (!$stmt) {
            $result['result'] = -1;
            $result['no'] = 1;
            $result['msg'] = $conn->errorInfo();

            return $result;
        }

        $sql = ' SELECT FOUND_ROWS() as recCount ';

        $stmTp = $conn->prepare($sql);
        $stmTp->setFetchMode(PDO::FETCH_ASSOC);
        $stmTp->execute();
        $rowP = $stmTp->fetch();

        $result['export']['recordsCount'] = $rowP['recCount'];

        while ($row = $stmt->fetch()) {
            $list[$row['Product_id']] = $row;
        }
        $result['result'] = 1;
        $result['export']['list'] = $list;

        return $result;
    }
    public static function getProduct($fields = '')
    {
        $conn = dbConn::getConnection();

        include_once ROOT_DIR.'/model/db.inc.class.php';

        $condition = DataBase::filterBuilder($fields);

        $sql = '
                select  SQL_CALC_FOUND_ROWS  *  from (  SELECT
                  `company_products`.*,
                  `company`.`company_name`
                FROM
                  `company_products`
                  LEFT JOIN `company` ON `company_products`.`company_id` =
                    `company`.`Company_id`) as t1 '.$fields['where'].$condition['list']['filter'].$condition['list']['order'].$condition['list']['limit'];

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        if (!$stmt) {
            $result['result'] = -1;
            $result['no'] = 1;
            $result['msg'] = $conn->errorInfo();

            return $result;
        }

        $sql = ' SELECT FOUND_ROWS() as recCount ';

        $stmTp = $conn->prepare($sql);
        $stmTp->setFetchMode(PDO::FETCH_ASSOC);
        $stmTp->execute();
        $rowP = $stmTp->fetch();

        $result['export']['recordsCount'] = $rowP['recCount'];

        while ($row = $stmt->fetch()) {
            $list[$row['Company_products_id']] = $row;
        }
        $result['result'] = 1;
        $result['export']['list'] = $list;

        return $result;
    }

    public static function getArticleEasy()
    {
        //global $lang;

        $conn = dbConn::getConnection();
        $sql = "SELECT
                    *
                FROM
                    article
                   ORDER BY 'date' DESC ";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if (!$stmt) {
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

    public static function pushRateDB($rate,$rate_product,$product_id)
    {
        //global $lang;

        $conn = dbConn::getConnection();

        $sql = "update artists_products set rate='$rate',rate_count='$rate_product' where Artists_products_id = '$product_id'";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if (!$stmt) {
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
