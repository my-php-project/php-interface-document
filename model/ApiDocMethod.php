<?php
namespace doc\model;
/**
 * @author blowsnow
 * @createdate 2020/9/8 16:27
 * @describe
 */
class ApiDocMethod  extends ApiDocModel
{
    public $url;

    public $method = 'GET';

    /** @var array<ApiDocParam> 参数列表 */
    public $params = [];

    /** @var array<ApiDocParam> 路径列表 */
    public $querys = [];

    public $return;
}
