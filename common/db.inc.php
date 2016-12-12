<?php

class dbConn{

    protected static $db;

    public function __construct() {

        try {
            self::$db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_DATABASE."", DB_USER, DB_PASSWORD);
        }
        catch (PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }

    }

    public static function getConnection() {

        if (!self::$db) {
            new dbConn();
        }

        return self::$db;
    }

}
