<?php
/**
 * Created by PhpStorm.
 * User: xqq
 * Date: 2018/1/28
 * Time: 13:19
 */

namespace frame;


class Route
{
    public static function dispatch($path)
    {
        list($path, $var) = static::parseUrl($path);
        if(isset($path)){
            $module = array_shift($path);
            $controller = !empty($path) ? array_shift($path) : null;
            $action = !empty($path) ? array_shift($path) : null;
        }
        $route = [isset($module)?$module:null, isset($controller)?$controller:null, isset($action)?$action:null];
        return ['route'=>$route];
    }

    public static function parseUrl($url)
    {
        $var = [];
        if(strpos($url, '?') !== false){
            $info = parse_url($url);
            $path = explode('/', $info['path']);
            parse_str($info['query'], $var);
        }elseif(strpos($url, '/') !== false){
            $path = explode('/', $url);
        }else{
            $path = [$url];
        }
        return [$path, $var];
    }

    public static function parseUrlParam($url)
    {

    }
}