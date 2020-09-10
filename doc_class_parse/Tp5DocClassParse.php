<?php

namespace doc\doc_class_parse;

/**
 * @package doc
 * @author blowsnow
 * @createdate 2020/9/8 16:48
 * @describe
 */
class Tp5DocClassParse extends DocClassParse
{

    public function get_class($path)
    {
        include_once $path;
        $path = str_replace("/","\\",$path);
        $path = str_replace("\\\\","\\",$path);
        $path = str_replace(".php","",$path);

        $str1 = substr($path,0,stripos($path,"\controller"));
        $str2 = substr($path,strrpos($str1,"\\"));

        $class = "app". $str2;

        return $class;
    }

    public function parse_url(\ReflectionClass $class, \ReflectionMethod $method)
    {
        preg_match('/\\\(.*)\\\controller/',$class->getNamespaceName(),$matches);
        $module = $matches[1];
        $controller = $class->getShortName();
        $action = $method->name;
        return $module."/".$controller."/".$action;
    }
}
