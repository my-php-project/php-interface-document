<?php

namespace doc\doc_class_parse;

/**
 * @package doc
 * @author blowsnow
 * @createdate 2020/9/8 16:31
 * @describe
 */
abstract class DocClassParse
{
    public abstract function get_class($path);

    public abstract function parse_url(\ReflectionClass $class, \ReflectionMethod $method);
}
