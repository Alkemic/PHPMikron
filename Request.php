<?php
/**
 * @author      Daniel Czuba <dc@danielczuba.pl>
 * @copyright   2013 Daniel Czuba
 * @license     http://www.wtfpl.net/ WTFPL
 */

namespace Mikron;

require_once 'Mikron/Base/Singleton.php';

use Mikron\Base\Singleton;

/**
 * Class Request
 *
 * @method static \Mikron\Request getInstance()
 * @package Mikron
 */
class Request extends Singleton{

    /**
     * @var array
     */
    protected $_post = array();

    /**
     * @var array
     */
    protected $_get = array();

    /**
     * @var string
     */
    protected $method;

    /**
     * Nazwa kontrolera
     *
     * @var string
     */
    protected $_module = '';

    /**
     * Nazwa kontrolera
     *
     * @var string
     */
    protected $_controller = 'Index';

    /**
     * Nazwa akcji
     *
     * @var string
     */
    protected $_action = 'index';

    /**
     * Tablica z parametrami przekazanymi
     *
     * @var array
     */
    protected $_params = array();

    /**
     * Dane wysłane przez POST
     *
     * @var array
     */
    protected $_params_post = array();

    /**
     *
     * @var string
     */
    private $_url = null;

    protected function __construct(){
        $parsed_url = parse_url($_SERVER['REQUEST_URI']);

        $this->_url = $parsed_url['path'];
        $get_params = array_key_exists('query', $parsed_url) ? $parsed_url['query'] : null;

        $segments = explode('/', $this->_url);

        if($segments[0] == '' ) unset($segments[0]);

        $this->method = strtolower($_SERVER['REQUEST_METHOD']);

//        if((count($segments) % 2) == 0){
//            $this->_controller = array_shift($segments);
//            $this->_action = array_shift($segments);
//
//            // jeśli kontroler nie istnieje, to zakładmay, że chodzi o Index
//            var_dump(file_exists(APP_PATH.'/Controller/'.ucfirst($this->_controller).'.php'));
//            if(!file_exists(APP_PATH.'/Controller/'.ucfirst($this->_controller).'.php')){
//                $segments[] = $this->_controller;
//                $segments[] = $this->_action;
//                $this->_controller = 'Index';
//                $this->_action = 'index';
//            }
//        }else{
//            if($this->getUrl() != '/')
//                $this->_action = array_shift($segments);
//        }
//
//        while((bool)count($segments)){
//            $this->_params[array_shift($segments)] = array_shift($segments);
//        }

        parse_str($get_params, $get_params_array);
        $this->_params = array_merge($this->_params, $get_params_array);

    }

    /**
     * Zwrata parametr o podanej nazwie, lub wartość domyślną
     *
     * @param   type $name
     * @param   string $default
     * @return  string
     */
    public function getParam($name, $default = null){
        return array_key_exists($name, $this->_params)?$this->_params[$name]:$default;
    }

    /**
     * Zwraca wszystkie parametry przekazane
     *
     * @param   bool $asObject
     * @return  array|stdClass
     */
    public function getParams($asObject = false){
        return $asObject?(object)$this->_params:$this->_params;
    }

    /**
     * @param $params
     * @return $this
     */
    public function setParams($params){
        $this->_params = $params;
        return $this;
    }

    /**
     *
     * @return  string
     */
    public function getUrl(){
        return $this->_url;
    }

    /**
     * @param $url
     * @return $this
     */
    public function setUrl($url){
        $this->_url = $url;
        return $this;
    }

    /**
     * Czy zapytanie wysłano POST'em
     *
     * @return  bool
     */
    public function isPost(){
        return strtolower($_SERVER['REQUEST_METHOD']) == 'post';
    }

    /**
     *
     * @return  string
     */
    public function getModuleName(){
        return $this->_module;
    }

    /**
     * @param $module
     * @return $this
     */
    public function setModuleName($module){
        $this->_module = $module;
        return $this;
    }

    /**
     *
     * @return  string
     */
    public function getControllerName(){
        return $this->_controller;
    }

    /**
     * @param $controller
     * @return $this
     */
    public function setControllerName($controller){
        $this->_controller = $controller;
        return $this;
    }

    /**
     *
     * @return  string
     */
    public function getActionName(){
        return $this->_action;
    }

    /**
     * @param $action
     * @return $this
     */
    public function setActionName($action){
        $this->_action = $action;
        return $this;
    }

}
