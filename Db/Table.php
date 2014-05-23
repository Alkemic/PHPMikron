<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Table
 *
 * @author  Daniel 'Alkemic' Czuba <dc@danielczuba.pl>
 */
abstract class Db_Table{

    /**
     *
     * @var string
     */
    protected $_primary_key = null;

    /**
     *
     * @var string
     */
    protected $_table = null;

    /**
     * @var \Mikron\Db\Connection
     */
    protected $_conection = null;

    /**
     * Zapisuje dane w tablicy do bazy danych
     *
     * @param   array $param
     * @return  bool or integer Zwraca ID nowego rekordu, lub FALSE w przypadku
     * niepowodzenia
     */
    public function insert(array $data){

    }

    /**
     *
     * @param   array $data
     * @param   array $where
     * @return  integer Ilość zaktualizowanych rekordów
     */
    public function update(array $data, array $where){

    }

    /**
     *
     * @return string Nazwa klucza głównego
     */
    public function getPrimaryKeyName(){
        return $this->_primary_key;
    }

    /**
     *
     * @return  strin Nawza tabeli
     */
    public function getTableName(){
        return $this->_table;
    }

    public function getConnection(){
        // if there is no connection
        if($this->_conection === null)
            $this->_conection = \Mikron\Db\Connection::getInstance();

        return $this->_conection;
    }
}
