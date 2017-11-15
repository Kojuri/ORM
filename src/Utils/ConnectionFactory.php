<?php
namespace Utils;
class ConnectionFactory
{
    private static $config;
    private static $db;
    
    public function __construct() {}

    public static function makeConnection($config){
        if(!isset(self::$db)){
            self::$config = $config;
            $dsn = "mysql:host=".self::$config['host'].";dbname=".self::$config['base'];
            try {
                /* Créer une instance de PDO (une connexion) */
                self::$db = new \PDO($dsn, 
                    self::$config['user'], 
                    self::$config['pass'], 
                    [\PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION]);
                return self::$db;
            } catch(\PDOException $e) {
                /* Erreur de connexion */
                echo "Connection error: $dsn" . $e->getMessage();
                exit;
            }
        }
        else{
            return self::$db;
        }
    }
    
    public static function getConnection(){
        if(isset(self::$db)){
            return self::$db;
        }
    }
}

