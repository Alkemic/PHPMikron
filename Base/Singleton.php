<?php
namespace Mikron\Base;
/*
 * Copyright and other bullshits...
 */

/**
 * Singleton
 *
 * @author  Daniel 'Alkemic' Czuba <dc@danielczuba.pl>
 */
abstract class Singleton{

    protected function __construct(){}

    /**
     * @return mixed
     */
    final public static function getInstance(){
        static $instances = array();

        $called_class_name = get_called_class();

        if(!isset($instances[ $called_class_name])){
            $instances[$called_class_name ] = new $called_class_name();
        }

        return $instances[$called_class_name];
    }

    final private function __clone(){}

}
