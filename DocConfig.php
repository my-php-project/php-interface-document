<?php

namespace doc;


use doc\doc_comment_parse\JetbrainsDocCommentParse;
use doc\doc_class_parse\Tp5DocClassParse;

/**
 * @package doc
 * @author blowsnow
 * @createdate 2020/9/8 15:46
 * @describe
 */
class DocConfig
{
    public $base_url = '';

    public $title = 'Api 接口';

    public $version = '1.0';

    public $description = '';

    // 接口目录，默认扫描所有目录
    public $base_path = '';

    public $doc_class_parse = Tp5DocClassParse::class;

    public $doc_comment_parse = JetbrainsDocCommentParse::class;

    public $cache = false;

    public $view = "swagger";
}
