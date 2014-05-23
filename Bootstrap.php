<?php
/**
 * @author      Daniel Czuba <dc@danielczuba.pl>
 * @copyright   2013 Daniel Czuba
 * @license     http://www.wtfpl.net/ WTFPL
 */

namespace Mikron;

/**
 * Class Bootstrap
 * @package Mikron
 */
class Bootstrap {

    public function run(){
        \Mikron\Event::trigger('Mikron\Bootstrap::run');
        $selfMethods = get_class_methods($this);

        foreach($selfMethods as $method){
            if(substr(strtolower($method), 0, 4) == 'init'){
                $this->$method();
                \Mikron\Event::trigger(get_called_class().'::'.$method);
            }
        }

    }

}
