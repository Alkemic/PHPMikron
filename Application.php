<?php
/**
 * @author      Daniel Czuba <dc@danielczuba.pl>
 * @copyright   2013 Daniel Czuba
 * @license     http://www.wtfpl.net/ WTFPL
 */

namespace Mikron;

require_once 'Mikron/Base/Singleton.php';
require_once 'Mikron/Request.php';
require_once 'Mikron/Exception.php';

use Mikron\Base\Singleton,
    Mikron\Request,
    Mikron\Exception;

/**
 * Główna klasa odpowiedzialna za start aplikacji i zarządzanie nią
 *
 * @method static \Mikron\Application getInstance()
 * @package Mikron
 */
class Application extends Singleton{

    /**
     *
     * @var Request
     */
    public $_request = null;

    static public function init(){
        /** @var $application Application */
        $application = self::getInstance();
        $application->autoload();

        $application->setRequest(Request::getInstance());

        $bootstrap = new \Bootstrap();
        $bootstrap->run();
        $application->dispatch()->run();

        return $application;
    }

    /**
     * Zwraca obiekt żądania
     *
     * @return  Request
     */
    public function getRequest(){
        return $this->_request;
    }

    /**
     * Ustawia obiekt żądania
     *
     * @param   Request $request
     * @return  Application
     */
    public function setRequest(Request $request){
        $this->_request = $request;
        return $this;
    }

    /**
     * /<controller>/<action>/<param_1>/<value_1>/<param_2>/<value_2>/.../<param_n>/<value_n>
     */
    public function dispatch(){
        /** @var $router Router */
        $router = Router::getInstance();
        $router->routeMatch($this->getRequest(), true);

        return $this;
    }

    /**
     *
     */
    public function autoload(){
        $mikronAutoloader = function($className){
            $parts = explode('\\', $className);
            $classFileName = implode('/', $parts) . '.php';

            $includePaths = explode(":", ini_get('include_path'));

            $fileExists = false;
            foreach($includePaths as $includePath){
                if(file_exists($includePath.DIRECTORY_SEPARATOR.$classFileName))
                    $fileExists = true;
            }

            if(!$fileExists)
                throw new \Mikron\Exception('File '.$classFileName.' for class "'.$className.'" doesn\'t exists');

            require_once $classFileName;
        };

        spl_autoload_register($mikronAutoloader);

        return $this;
    }

    public function run(){
        try{
            $this->execute();
        }catch(\Exception $exc){
            $errorController = new \Controller\Error;
            $errorController->exception = $exc;
            $errorController->errorAction();
        }

        return $this;
    }

    public function execute(){
        $request = $this->getRequest();
        $controllerName = ($request->getModuleName()?$request->getModuleName().'\\':'').'Controller\\'.ucfirst($this->getRequest()->getControllerName());
        /** @var $controller \Mikron\Controller */
        $controller = new $controllerName();
//        $controller->setR
//        var_dump($controller);

        $actionName = $this->getRequest()->getActionName().'Action';

        if(!method_exists($controller, $actionName) and !method_exists($controller, '__call'))
            throw new \Mikron\Controller\Exception('Method '.$actionName.'(), does not exists.');

        $controller->$actionName();

        $controller->view->run();
    }
}
