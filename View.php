<?php
/**
 * @author      Daniel Czuba <dc@danielczuba.pl>
 * @copyright   2013 Daniel Czuba
 * @license     http://www.wtfpl.net/ WTFPL
 */

namespace Mikron;
use Mikron\Base\Singleton;
use Mikron\Helper;

/**
 * @package Mikron
 */
class View{

    /**
     * @var Request
     */
    protected $request = null;

    public function __construct(Request $request){
        $this->request = $request;
        $request->getActionName();
    }

    public function getName(){}

    /**
     * @throws Exception
     */
    public function run(){
        $templatePathParts = [ROOT_PATH, 'templates'];
        if($this->request->getModuleName())
            $templatePathParts[] = Helper\String::camelCaseToDash($this->request->getModuleName());

        $templatePathParts[] = Helper\String::camelCaseToDash($this->request->getControllerName());
        $templatePathParts[] = Helper\String::camelCaseToDash($this->request->getActionName());
        $templatePath = join(DIRECTORY_SEPARATOR, $templatePathParts).'.phtml';

        if(!file_exists($templatePath) or !is_file($templatePath))
            throw new Exception('Template "'.$templatePath.'" doesn\'t exists.');

        require_once $templatePath;
    }

}
