<?php
/**
 * @author      Daniel Czuba <dc@danielczuba.pl>
 * @copyright   2013 Daniel Czuba
 * @license     http://www.wtfpl.net/ WTFPL
 */

namespace Mikron;

use Mikron\Exception,
    Mikron\Base\Singleton;

/**
 * Class Router
 *
 * @method static \Mikron\Router getInstance()
 * @package Mikron
 */
class Router extends Singleton{

    /**
     * @var array
     */
    protected $routingTable = array(
//        'default1' => array('pattern' => '/^\/(?<module>.*)\/(?<controller>.*)\/(?<action>.*)/i', 'params' => array()),
//        'default2' => array('pattern' => '/^\/(?<controller>.*)\/(?<action>.*)/i', 'params' => array('module' => 'Index')),
//        'default3' => array('pattern' => '/^\/(?<action>.*)/i', 'params' => array('module' => 'Index', 'controller' => 'Index',)),
//        'name'  => array('pattern' => 'pattern', 'params' => array('module', 'controller', 'action', 'paramsStack')),
//        'news'  => array('pattern' => '/^\/news\/(\d+<id>),(.*<slug>)/i', 'params' => array('module' => 'Index', 'controller' => 'Index', 'action' => 'index')),
//        'news2' => array('pattern' => '/^\/news\/(?<id>\d+),(?<slug>.*)/i', 'params' => array('module' => 'Index', 'controller' => 'Index', 'action' => 'index')),
//        'news3' => array('pattern' => '/^\/news\/(?<id>\d+),(?<slug>.*).html/i', 'params' => array('module' => 'Index', 'controller' => 'Index', 'action' => 'index')),
//        'news4' => array('pattern' => '/^\/news\/(?<id>\d+),(?<slug>.*).html/i', 'params' => array('module' => 'Index', 'controller' => 'Index', 'action' => 'index')),
//        'news5' => array('pattern' => '/^\/news\/123,asdasd-asdasd.html/i', 'params' => array('module' => 'Index', 'controller' => 'Index', 'action' => 'index')),
//        'asd' => array('pattern' => '/^\/news\/(?<id>\d+),(?<slug>.*).html/i', 'params' => array('module' => 'Index', 'controller' => 'Index', 'action' => 'index')),
    );

    /**
     * @param $name
     * @param array $routeDefinition
     * @return $this
     * @throws Exception
     */
    public function addRoute($name, $pattern, array $params = array()){
        $this->routingTable[$name] = array('pattern' => $pattern, 'params' => $params);
        return $this;
    }

    /**
     * @param $url
     * @return bool
     */
    public function routeMatch(\Mikron\Request $request, $setRequest = false){
        $url = $request->getUrl();
        $matchOccurs = false;

        // finding matching pattern to our url
        foreach($this->routingTable as $routeName => $routeConfiguration){
            $matchOccurs = preg_match($routeConfiguration['pattern'], $url, $matches);

            if($matchOccurs === 1) break;
        }

        // we haven't found any regexp match to provided url
        if($matchOccurs !== 1) return false;

        $params = array();
        $keys = $this->getRegexNamed($routeConfiguration['pattern']);
        foreach($keys as $key)
            if(array_key_exists($key, $matches)) $params[$key] = $matches[$key];

        if($setRequest){
            $request->setModuleName(in_array('module', $keys)?$params['module']:$routeConfiguration['params']['module']);
            $request->setControllerName(in_array('controller', $keys)?$params['controller']:$routeConfiguration['params']['controller']);
            $request->setActionName(in_array('action', $keys)?$params['action']:$routeConfiguration['params']['action']);

            $request->setParams($params);
        }

        return $matches;
    }

    public function getRegexNamed($pattern){
        preg_match_all('/\<(.*)\>/U', $pattern, $matches);

        if(array_key_exists(1, $matches)) return $matches[1];

        return false;
    }

}
