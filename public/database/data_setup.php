<?php
namespace di\database;
require_once('init_pdo.php');

class DataSetup
{
    use app_PDO;

    function installDB()
    {

        $db_name = "contacts";
        $table_name = "contact_info";
        $pdo = $this->get_pdo(true);
         
        
        try {
          echo ("attempting to create DB <br/>");
            $create_db_stmt = $pdo->exec("CREATE DATABASE  IF NOT EXISTS {$db_name};
            USE {$db_name};");
            echo ("DB Created <br/>");
            echo ("attempting to table <br/>");
            $create_table_stmt = $pdo->query("CREATE TABLE IF NOT EXISTS {$table_name}(
      id INT(11) AUTO_INCREMENT PRIMARY KEY,
      fullName VARCHAR(50) NOT NULL, 
      email VARCHAR(75) NOT NULL,
      phone VARCHAR(25) NULL,
      memo  TEXT NOT NULL,
      date_entered TIMESTAMP DEFAULT CURRENT_TIMESTAMP
     );");
            echo("database {$db_name} and the {$table_name} table is ready to use!");

        } catch (Exception $e) {
            var_dump($e);
            echo "Unable to Create Initial Database. Please check configuration";
            die();
        }
    }
}

(new DataSetup())->installDB();