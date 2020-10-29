<?php

namespace App\Models;
require(__DIR__ .'/../../vendor/autoload.php');
require_once('GeneralFunctions.php');

use Exception;
use http\Exception\RuntimeException;
use PDOException;
use Dotenv\Dotenv;

/**
 * Created by PhpStorm.
 * User: Diego-PC
 * Date: 10/12/2019
 * Time: 9:17
 */

abstract class BasicModel {
    //TODO: Agregar PHPDoc
    public $isConnected;
    protected $datab;

    # métodos abstractos para ABM de clases que hereden
    abstract protected static function search($query);
    abstract protected static function getAll();
    abstract protected static function searchForId($id);
    abstract protected function create();
    abstract protected function update();
    abstract protected function deleted($id);

    public function __construct(){

        $this->isConnected = true;
        try {
            GeneralFunctions::loadEnv(['DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'],['DB_PORT']);

            if(array_search($_ENV['DB_CONNECTION'], \PDO::getAvailableDrivers()) !== false){
                $this->datab = new \PDO(
                    ($_ENV['DB_CONNECTION'] != "sqlsrv") ?
                        "{$_ENV['DB_CONNECTION']}:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_DATABASE']};charset={$_ENV['DB_CHAR_SET']}" :
                        "{$_ENV['DB_CONNECTION']}:Server={$_ENV['DB_HOST']},{$_ENV['DB_PORT']};database={$_ENV['DB_DATABASE']}",
                    $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
                );
                $this->datab->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $this->datab->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
                $this->datab->setAttribute(\PDO::ATTR_PERSISTENT, true);
            }else{
                throw new Exception('Driver de BD no soportado por el servidor');
            }
        }catch(\PDOException $e) {
            $this->isConnected = false;
            throw new Exception($e->getMessage());
        }catch (Exception $e){
            $this->isConnected = false;
            throw $e;
        }
    }

    //disconnecting from database
    //$database->Disconnect();
    public function Disconnect(){
        $this->datab = null;
        $this->isConnected = false;
    }



    //Getting row -> Deveulve una sola fila de la Base de Datos.
    //$getrow = $database->getRow("SELECT email, username FROM users WHERE username = ? and password = ?", array("diego","123456"));
    public function getRow($query, $params = array()){
        try{
            $stmt = $this->datab->prepare($query);
            $stmt->execute($params);
            return $stmt->fetch();
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    //Getting multiple rows
    //$getrows = $database->getRows("SELECT id, username FROM users");
    public function getRows($query, $params=array()){
        try{
            $stmt = $this->datab->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    //Getting last id insert
    //$getrows = $database->getLastId();
    public function getLastId(){
        try{
            return $this->datab->lastInsertId();
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    //inserting un campo
    //$insertrow = $database ->insertRow("INSERT INTO users (username, email) VALUES (?, ?)", array("Diego", "yusaf@email.com"));
    public function insertRow($query, $params){
        try{
            if (is_null($this->datab)){
                $this->__construct();
            }
            $stmt = $this->datab->prepare($query);
            for ($i=0;$i<count($params);$i++){
                $stmt->bindValue($i+1, $params[$i]);
            }
            return $stmt->execute();
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    //updating existing row
    //$updaterow = $database->updateRow("UPDATE users SET username = ?, email = ? WHERE id = ?", array("yusafk", "yusafk@email.com", "1"));
    public function updateRow($query, $params){
        return $this->insertRow($query, $params);
    }

    //delete a row
    //$deleterow = $database->deleteRow("DELETE FROM users WHERE id = ?", array("1"));
    public function deleteRow($query, $params){
        return $this->insertRow($query, $params);
    }
}
