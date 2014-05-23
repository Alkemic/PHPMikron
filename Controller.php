<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Mikron;
use Mikron\Event;

/**
 * Description of Controller
 *
 * @author alkemic
 */
class Controller{
    /**
     *
     * @var Request
     */
    private $_request = null;

    /**
     * @var View
     */
    public $view = null;

    /**
     *
     */
    public function init(){
        Event::trigger('Mikron\Controller::init');
    }

    /**
     *
     */
    public function __construct(){
//        var_dump(__METHOD__);
        Event::trigger('Mikron\Controller::__constructor');
        $this->_request = $request = Request::getInstance();

        $this->view = new View($request);
    }

    /**
     *
     * @return  Request
     */
    public function getRequest(){
        return $this->_request;
    }

    /**
     *
     * @return  Request
     */
    public function setRequest(){
        return $this->_request;
    }

}
