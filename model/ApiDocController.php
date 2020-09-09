<?php

namespace doc\model;

/**
 * @package doc\model
 * @author blowsnow
 * @createdate 2020/9/8 17:11
 * @describe
 */
class ApiDocController
{
    public $title;

    public $desc;

    /** @var array<ApiDocMethod> 接口列表 */
    public $methods;
}
