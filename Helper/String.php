<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alkemic
 * Date: 26.10.13
 * Time: 18:15
 * To change this template use File | Settings | File Templates.
 */

namespace Mikron\Helper;


class String {

    /**
     * Zamienia zapisz CamelCase na odpowiednik z pauzami (camel-case)
     *
     * @param $str
     * @return string
     */
    static function camelCaseToDash($str){
        $callback = create_function('$matches', 'return \'-\'.mb_strtolower($matches[0]);');
        return preg_replace_callback('/([A-Z])/', $callback, lcfirst($str));
    }

    /**
     * Zamienia dash-case na odpowiednik w camelCase
     *
     * @param $str
     * @return string
     */
    static function dashToCamelCase($str){
        $callback = create_function('$matches', 'return mb_strtoupper($matches[0]);');
        return preg_replace_callback('/(\-[a-z])/', $callback, ucfirst($str));
    }

}
