<?php
namespace di\database;
require('init_pdo.php');
require('iDataFunctions.php');

use \PDO;
class DataHandler implements iDataFunctions
{
    use app_PDO;
    private $contact;
    private $pdo;


    public function __construct(\di\models\BaseModel $contact = null)
    {
        //Todo: Generecize this class to read the toJson() and commit the object.

        $this->contact = $contact;
        $this->pdo = $this->get_pdo();

    }

    function create()
    {
        $table = DB_CONTACT_TABLE;
        try {
            $stmt = $this->pdo->prepare("INSERT INTO {$table}(
                  fullName, 
                  email,
                  phone,
                  memo) VALUES (?,?,?,?)");
            $stmt->execute([$this->contact->getName(), $this->contact->getEmail(), $this->contact->getPhone(), $this->contact->getMemo()]);
            return true;
        } catch (Exception $e) {
            //Todo: deal  with db errors
            return false;
        }
    }

    function delete()
    {
        // TODO: Implement delete() method.
    }

    function update()
    {
        // TODO: Implement update() method.
    }

    function fetch($whereClause = null)
    {
        try {
            //Todo: Make robust for sql injection
            $sql = ($whereClause != null) ? "SELECT fullName,
                    email,
                    phone,
                    memo
               FROM contact_info
              WHERE {$whereClause}" : "SELECT fullName,
                    email,
                    phone,
                    memo
               FROM contact_info LIMIT 50";
            $query = $this->pdo->prepare($sql);
            $query->execute();
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $buf = [];
            while ($r = $query->fetch()) {
                array_push($buf, $r);
            }
            return $buf;
        } catch (Exception $e) {
            //Todo: deal  with db errors
            return false;
        }
    }

    function adhoc($sql )
    {
        try {
            $query = $this->pdo->prepare($sql);
            $query->execute();
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $buf = [];
            //Todo: return iterator
            while ($r = $query->fetch()) {
                array_push($buf, $r);
            }
            return $buf;
        } catch (Exception $e) {
            //Todo: deal  with db errors
            return false;
        }
    }
}