<?php

namespace doc;

/**
 * @package doc
 * @author blowsnow
 * @createdate 2020/9/8 15:47
 * @describe
 */
class DocScanner
{

    //扫描目录列表
    public static function scan($dir)
    {
        $files=array();
        $dir_list = scandir($dir);
        foreach($dir_list as $file){
            if($file!='..' && $file!='.'){
                if(is_dir($dir.'/'.$file)){
                    array_push($files,...self::scan($dir.'/'.$file . '/'));
                }else{
                    $files[] = $dir . $file;
                }
            }
        }
        return $files;
    }
}
