<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Mikron\Db;

/**
 * Wrapper around PDO
 *
 * @author  Daniel 'Alkemic' Czuba <dc@danielczuba.pl>
 */
class Connection extends \Mikron\Base\Singleton{

    /**
     * PDO adapter
     *
     * @var \PDO
     */
    protected $_adapter = null;

    protected function __construct(){}

    public function setupConnection($dsn, $username = null, $password = null, $options = null){
        $this->_adapter = new \PDO($dsn, $username, $password, $options);
    }

    public function prepare($statement, array $driver_options = null){
        return $this->_adapter->prepare($statement, $driver_options);
    }

    public function beginTransaction(){
        return $this->_adapter->beginTransaction();
    }

    public function commit(){
        return $this->_adapter->commit();
    }

    public function rollBack(){
        return $this->_adapter->rollBack();
    }

    public function inTransaction(){
        return $this->_adapter->inTransaction();
    }

    public function setAttribute($attribute, $value){
        return $this->_adapter->setAttribute($attribute, $value);
    }

    public function exec($statement){
        return $this->_adapter->exec($statement);
    }

    public function query($statement){
        return $this->_adapter->query($statement);
    }

    public function lastInsertId($name = null){
        return $this->_adapter->lastInsertId($name);
    }

    public function errorCode(){
        return $this->_adapter->errorCode();
    }

    public function errorInfo(){
        return $this->_adapter->errorInfo();
    }

    public function getAttribute($attribute){
        return $this->_adapter->getAttribute($attribute);
    }

    public function quote($string, $parameter_type = \PDO::PARAM_STR) {
        return $this->_adapter->quote($string, $parameter_type);
    }
    public static function getAvailableDrivers () {
        return \PDO::getAvailableDrivers();
    }

}
