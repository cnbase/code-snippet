<?php
/**
 * 简易Mysql操作类
 * Created by PhpStorm.
 * User: NaobuOrg
 * Date: 2017/9/23
 * Time: 15:07
 */

/**
 * 使用方法：
 * $obj = iMySql::instance($host,$user,$password,$db,$port,$drive='pdo|mysqli');
 * $obj->query($sql); //查询
 * $obj->execute($sql); //执行
 * Class iMySql
 */
class iMySql{
    public static $obj;
    public static $drive;
    public static $db;

    public static function instance($host,$user,$password,$db,$port = '3306',$drive = 'pdo')
    {
        if (is_object(self::$obj)){
            return self::$obj;
        } else {
            self::$obj = new self();
            if (strtolower($drive) == 'pdo'){
                $dsn = "mysql:dbname={$db};host={$host}";
                try{
                    self::$db = new \PDO($dsn,$user,$password);
                } catch (PDOException $e){
                    die($e->getMessage());
                }
                self::$drive = 'pdo';
            } else {
                self::$db = new \mysqli($host,$user,$password,$db,$port);
                if (self::$db->connect_error){
                    die(self::$db->connect_error);
                }
                self::$drive = 'mysqli';
            }
            return self::$obj;
        }
    }

    /**
     * 执行
     * @param $sql
     * @return bool
     */
    public function execute($sql){
        self::showConnectErr();
        if (!$sql){
            return false;
        }
        if (self::$drive == 'pdo'){
            $result = self::$db->exec($sql);
            if ($result === false){
                return false;
            } else {
                return true;
            }
        } else {
            $result = self::$db->query($sql);
            if ($result === true){
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 查询
     * @param $sql
     * @return array|bool
     */
    public function query($sql)
    {
        self::showConnectErr();
        if (!$sql){
            return false;
        }
        if (self::$drive == 'pdo'){
            $statement = self::$db->prepare($sql);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } else {
            $result = self::$db->query($sql);
            if ($result){
                return $result->fetch_array();
            } else {
                return [];
            }
        }
    }

    public static function showConnectErr()
    {
        if (!self::checkDb()){
            die('no mysql handle object.');
        }
    }

    public static function checkDb()
    {
        if (is_object(self::$obj)){
            return true;
        } else {
            return false;
        }
    }
}