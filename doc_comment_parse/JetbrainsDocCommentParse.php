<?php

namespace doc\doc_comment_parse;

use doc\model\ApiDocMethod;
use doc\model\ApiDocController;
use doc\model\ApiDocParam;

/**
 * @package doc
 * @author blowsnow
 * @createdate 2020/9/8 17:25
 * @describe Jetbrains注解解析器
 */
class JetbrainsDocCommentParse extends DocCommentParse
{

    private $alias = [
        'describe' => 'desc',

    ];

    public function parse_controller($docComment)
    {
        $comments = $this->parse($docComment);


        $apiDocController = new ApiDocController();

        if (isset($comments['title'])){
            $apiDocController->title = strtolower($comments['title']);
        }
        if (isset($comments['desc'])){
            $apiDocController->desc = $comments['desc'];
        }
        if (isset($comments['return'])){
            $apiDocController->return = $comments['return'];
        }
        if (isset($comments['weight'])){
            $apiDocController->weight = $comments['weight'];
        }

        return $apiDocController;
    }

    public function parse_method($docComment,$url)
    {
        $comments = $this->parse($docComment);
        $apiDocMethod = new ApiDocMethod();

        // 解析方法访问地址
        if (isset($comments['url'])){
            $apiDocMethod->url = $comments['url'];
        }else{
            $apiDocMethod->url = $url;
        }
        // 解析请求方式
        if (isset($comments['method'])){
            $apiDocMethod->method = strtoupper($comments['method']);
        }
        if (isset($comments['title'])){
            $apiDocMethod->title = strtolower($comments['title']);
        }
        if (isset($comments['desc'])){
            $apiDocMethod->desc = $comments['desc'];
        }
        if (isset($comments['return'])){
            $apiDocMethod->return = $comments['return'];
        }
        if (isset($comments['weight'])){
            $apiDocMethod->weight = $comments['weight'];
        }
        if (isset($comments['param'])){
            $params = [];
            foreach ($comments['param'] as $param){
                $params[] = $this->parse_params($param);
            }
            $apiDocMethod->params = $params;
        }


        if (isset($comments['query']) && $comments['query']){
            $params = [];
            foreach ($comments['query'] as $param){
                $params[] = $this->parse_querys($param);
            }
            $apiDocMethod->querys = $params;
        }

        //GET请求不会有参数的  只有访问参数，所以做个转换
        if ($apiDocMethod->method == 'GET' && count($apiDocMethod->params)){
            array_push($apiDocMethod->querys,...$apiDocMethod->params);
            $apiDocMethod->params = [];
        }


        return $apiDocMethod;
    }

    private function parse_params($param_str){
        $params = preg_split("/\s+/",$param_str);
        $apiDocParam = new ApiDocParam();

        if (strpos($params[0],"$") !== false){
            $apiDocParam->title = substr($params[0],1);
            $apiDocParam->type = $params[1];
        }else{
            $apiDocParam->title = substr($params[1],1);
            $apiDocParam->type = $params[0];
        }

        $apiDocParam->desc = implode(PHP_EOL,array_slice($params,2));
        return $apiDocParam;
    }

    private function parse_querys($param_str){
        $params = preg_split("/\s+/",$param_str);
        $apiDocParam = new ApiDocParam();
        if (strpos($params[0],"$") !== false){
            $apiDocParam->title = substr($params[0],1);
        }else{
            $apiDocParam->title = $params[0];
        }

        $apiDocParam->desc = implode(PHP_EOL,array_slice($params,1));
        return $apiDocParam;
    }

    private function parse($docComment){
        $docComment = str_replace("/*","",$docComment);
        $docComment = str_replace("*/","",$docComment);
        $docComment = preg_replace("/[^\n]*\*/","",$docComment);
        $docComments = explode("@",$docComment);
        $comments = [];
        foreach ($docComments as $comment){
            if (preg_match("/(.*?)\s+([\s\S]*)/",$comment,$matches)){
                $name = $matches[1]?:'title';
                $value = trim($matches[2]);

                if ($name == 'param' || $name == 'query'){
                    if (isset($comments[$name])){
                        array_push($comments[$name],$value);
                    }else{
                        $comments[$name] = [$value];
                    }
                }else if ($name == 'title'){
                    $temp = preg_split("/\n/",$value);
                    $comments[$name] = $temp[0];
                    if (count($temp) > 1){
                        $comments['desc'] = implode(PHP_EOL,array_slice($temp,1));
                    }
                }else{
                    $comments[$name] = $value;
                }
            }
        }
        return $this->parse_alias($comments);
    }

    private function parse_alias($comments){
        foreach ($this->alias as $name=>$name2){
            if (isset($comments[$name])){
                $comments[$name2] = $comments[$name];
            }
        }
        return $comments;
    }
}
