<?php
namespace di\database;
use \PDO;
require (__DIR__.'/../ini/data_config.php');

trait app_PDO{
    function get_pdo($init = false)
    {

      
        if($init){
            $dsn = "mysql:host=" . DB_HOST .  ";charset=" . DB_CHARSET;
        } else {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        }
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            
        ];
        try {
          return $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $opt);
        } catch (Exception $e) {
            echo "Unable to  connect. Please check configuration";
            die();
        }
    }
}