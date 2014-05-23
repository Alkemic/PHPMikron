<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Mikron;

/**
 * Description of Model
 *
 * @author  Daniel 'Alkemic' Czuba <dc@danielczuba.pl>
 */
abstract class Model{

    /**
     * Tablica z nazwami kolumn które nie należy zapisywać/auaktualniać, bo
     * są zarządzane z poziomu mysql
     *
     * @var array
     */
    protected $exclude = array();

    /**
     *
     * @var string
     */
    protected $table;

    /**
     * Ustawia klucz główny
     *
     * @param   type $id
     * @return  self Bierzący model
     */
    public function setPrimaryKey($id){
        $pk_name = $this->getDbTable()->getPrimaryKeyName();

        $this->$pk_name = $id;
        return $this;
    }

    /**
     * Pobiera wartość klucza głównego
     *
     * @return string Klucz główny
     */
    public function getPrimaryKey(){
        $pk_name = $this->getDbTable()->getPrimaryKeyName();

        return $this->$pk_name;
    }

    /**
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value) {
        $method = 'set' . $name;
        $variable = '_'. strtolower(substr($name, 3));
        $properties = get_class_vars(get_class($this));
        if(($name == 'mapper') || !array_key_exists( '_'.$name, $properties)){
            throw new Exception_Model('Invalid property');
        }

        $this->$method($value);
    }

    /**
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name) {
        $method = 'get' . $name;
        $variable = '_'. strtolower(substr($name, 3));
        $properties = get_class_vars(get_class($this));

        if(($name == 'mapper') || !array_key_exists( '_'.$name, $properties)){
            throw new Exception_Model('Invalid property');
        }

        return $this->$method();
    }

    /**
     * Obsługuje set<naza-pola> i get<nazwa-pola>
     * Obsługuje właściwości prywatne o nazwie _nazwa_jakeigos_pola przez metodę
     * setNazwaJakiegosPola(123) i getNazwaJakiegosPola()
     *
     * @param string $name
     * @param mixed $args
     * @return void|mixed
     */
    public function __call($name, $args){
        $action = substr($name, 0, 3);

        $callback = create_function('$matches', 'return \'_\'.strtolower($matches[0]);'); // lamba, wo bist du?!
        $variable = preg_replace_callback('/([A-Z])/', $callback, ucfirst(substr($name, 3)));

        if($action == 'set'){
            $this->$variable = $args[0];
            return $this;
        }
        // nic na wiarę ;-)
        elseif($action == 'get'){
            return $this->$variable;
        }else{
            throw new Exception_Model('Incorect method name (' . $name . ')');
        }
    }

    /**
     *
     * @param array $options Tablica asocjacyjna z wartościami
     */
    public function __construct($options = null){
        if(is_array($options)){
            $this->setOptions($options);
        }
    }

    /**
     *
     * @param   array $options
     * @return  self
     */
    public function setOptions(array $options){
        $methods = get_class_methods($this);
        $properties = get_class_vars(get_class($this));

        foreach($options as $key => $value){
            $callback = create_function('$matches', 'return strtoupper(substr( $matches[0], 1));'); // lamba, wo bist du?!
            $method = 'set' . preg_replace_callback('/(_[a-z])/', $callback, $key);

            if(array_key_exists( '_'.$key, $properties)){
                $this->$method($value);
            }
        }

        return $this;
    }

    /**
     * Zwraca tablicę danych do zapisu z wyłączeniem tych kolumn które są w
     * atrybucie $exclude i bez PK
     *
     * @return array
     */
    protected function _asTableFiltered(){
        $exclude = $this->exclude;
        $exclude[] = $this->getDbTable()->getPrimaryKeyName();
        $data = $this->asTable();

        foreach($exclude as $key){
            if(array_key_exists($key, $data))
                unset($data[ $key ]);
        }

        return $data;
    }

    /**
     *
     * @return Model or int
     */
    public function save(){
        $data = $this->_asTableFiltered();

        try {
            if(null === ($id = $this->getId())){
                return $this->setId($this->getDbTable()->insert($data));
            }else{
                return $this->getDbTable()->update($data, array('id = ?' => $id));
            }
        }catch(Exception $e){
            throw new Exception_Model('Nie udało się zapisać' . PHP_EOL . $e->getMessage());
        }
    }

    /**
     *
     * @param   string $dbTable
     * @return  self
     */
    public function setDbTable($dbTable){
        if(is_string($dbTable))
            $dbTable = new $dbTable();

        if(!$dbTable instanceof Db_Table)
            throw new Exception_Model('Invalid table data gateway provided');

        $this->table = $dbTable;

        return $this;
    }

    /**
     *
     * @return  Db_Table
     */
    abstract public function getDbTable();

    /**
     * Istnieje tylko po to aby w klasie dziedziczącej łatwiej było zarządzać
     */
    abstract public function find($id);

    /**
     * Znajduje wpis po ID
     *
     * @param   integer $id
     * @return  self
     */
    protected function _find($id){
        $calledClassName = get_called_class();
        /** @var $model Model */
        $model = new $calledClassName();
        $model->setPrimaryKey($id);
        $row = $model->getDbTable()->find($id)->current();

        if(!$row){
            throw new Exception_Model('Press Entry with id ' . $id . ' not found');
        }else{
            $model->setOptions($row->toArray());
        }

        return $model;
    }

    /**
     * @return array
     */
    public function asTable(){
        $model_vars = get_class_vars(get_called_class());
        $data = array();

        foreach($model_vars as $key => $val){
            if(substr($key, 0, 1) == '_'){
                $key = substr($key, 1);
                $data[ $key ] = $this->$key;
            }
        }

        return $data;
    }


    /**
     * Ma na zadaniu przygotować dane przed zapisem do bazy, jest abstrakcyjna,
     * żeby o niej nie zapomnieć
     */
    abstract protected function _prepareData(array $data);
}
