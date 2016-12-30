<?php
/**
 * Created by PhpStorm.
 * User: FaridCS
 * Date: 10/28/2014
 * Time: 12:47 PM.
 */
class DataBase
{
    protected static $conn;

    public function __construct()
    {
        try {
            self::$conn = new PDO('mysql:host='.DB_HOST.';dbname='.DB_DATABASE.'', DB_USER, DB_PASSWORD);
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection Error: '.$e->getMessage();
        }
    }

    protected static function getConnection()
    {
        if (!self::$conn) {
            new self();
        }
        self::$conn->exec('SET character_set_database=UTF8');
        self::$conn->exec('SET character_set_client=UTF8');
        self::$conn->exec('SET character_set_connection=UTF8');
        self::$conn->exec('SET character_set_results=UTF8');
        self::$conn->exec('SET character_set_server=UTF8');
        self::$conn->exec('SET names UTF8');

        return self::$conn;
    }

    public static function filterBuilder($fields)
    {
        global  $lang;

        $limit = '';

        if (isset($fields['limit']['start']) && $fields['limit']['length'] != -1) {
            $limit = ' LIMIT '.intval($fields['limit']['start']).', '.intval($fields['limit']['length']);
        }
        $order = '';

        if (isset($fields['order']) && count($fields['order'])) {
            $orderBy = array();
            foreach ($fields['order'] as $sort_fields => $dir) {
                if ($dir != 'DESC' and $dir != 'ASC') {
                    continue;
                }
                $orderBy[] = '`'.$sort_fields.'` '.$dir;
            }
            $order = ' ORDER BY '.implode(', ', $orderBy);
        }
        $filter = '';
        $flag_trash = 0;


                
        if (isset($fields['filter'])) {
            foreach ($fields['filter'] as $filter_fields => $searchKey) {
                if (strpos($filter_fields, 'trash') == 'true') {
                    $flag_trash = 1;
                    if ($fields['useTrash'] !== 'false') {
                        $columnSearch[] = '`'.$filter_fields."` = '".$searchKey."'";
                        continue;
                    }
                }

                $columnSearch[] = '`'.$filter_fields."` LIKE '%".$searchKey."%'";
            }
        }

        if (count($columnSearch)) {
            $filter = $filter === '' ?
                implode(' AND ', $columnSearch) :
                $filter.' AND '.implode(' AND ', $columnSearch);
        }

        if ($filter != '' or $fields['where']!= '' ) {
            $result['list']['useWhere'] = ' WHERE ';

        }

        $result['result'] = 1;
        $result['list']['filter'] = $filter;
        $result['list']['order'] = $order;
        $result['list']['limit'] = $limit;
        $result['list']['WHERE'] = $fields['where'];
        $result['start'] = $fields['limit']['start'];

        $result['length'] = $fields['limit']['length'];

        return $result;

        /////////////////////////

        //or WHERE    news_id='$id' ");

        /*$stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $stmt->execute();

        if (!$stmt) {
            $res['result'] = -1;
            $res['no'] = 1;
            $res['msg'] = $conn->errorInfo();

            return $res;
        }

        //echo $stmt->rowCount();

        while ($row = $stmt->fetch()) {
            $this->_set_newsListDb($row['newsID'], $row);
        }

        $res['result'] = 1;
        $res['no'] = 2;
        //$res['list']=$fields;
        return $res;*/
    }
}
