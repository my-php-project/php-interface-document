<?php
namespace doc\doc_comment_parse;
use doc\model\ApiDocMethod;
use doc\model\ApiDocController;

/**
 * @author blowsnow
 * @createdate 2020/9/8 17:23
 * @describe
 */
abstract class DocCommentParse
{
    /**
     * 解析controller
     * @return ApiDocController
     */
    public abstract function parse_controller($docComment);

    /**
     * 解析方法
     * @return ApiDocMethod
     */
    public abstract function parse_method($docComment,$url);
}
