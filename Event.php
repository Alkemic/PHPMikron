<?php
/**
 * @author      Daniel Czuba <dc@danielczuba.pl>
 * @copyright   2013 Daniel Czuba
 * @license     http://www.wtfpl.net/ WTFPL
 */

namespace Mikron;
use Mikron\Base\Singleton;

/**
 * @method static \Mikron\Event getInstance()
 * @package Mikron
 */
class Event extends Singleton{

    /**
     * @var array Array of arrays with registered levels and it's what's to do
     */
    protected $registeredLevels = array();

    /**
     * @param string $level name of registered level
     * @param mixed $code Closure or function name ie: \User\Helper::doSmth
     */
    static function register($level, $code){
        $self = self::getInstance();

        if(!array_key_exists($level, $self->registeredLevels)) $self->registeredLevels[$level] = array();
        $self->registeredLevels[$level][] = $code;
    }

    /**
     * @param string $level Name of level
     * @param bool $params
     */
    static function trigger($level, $params = false){
        $self = self::getInstance();
        // find level in stack
        $stack = $self->getLevel($level);
        if(!$stack) return;

        // execute code
        foreach($stack as $entry){
            if($entry instanceof \Closure){
                if($params !== false)
                    $entry($params);
                else
                    $entry($params);
            }elseif(is_string($entry)){
                if($params !== false)
                    call_user_func_array($entry, array($params));
                else
                    call_user_func($entry);
            }
        }
    }

    /**
     * @param string $level
     * @return bool|array
     */
    public function getLevel($level){
        if(array_key_exists($level, $this->registeredLevels)) return $this->registeredLevels[$level];

        return false;
    }

}
