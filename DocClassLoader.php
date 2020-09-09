<?php

namespace doc;

/**
 * @package doc
 * @author blowsnow
 * @createdate 2020/9/8 16:00
 * @describe
 */
class DocClassLoader
{
    public static function loadClass($class){
        if (strstr($class,"doc")){
            include_once str_replace("\\",'/',DOC_PATH.'/../'.$class.'.php');
        }
    }
}
