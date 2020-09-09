<?php

namespace doc\doc_json;

use doc\DocConfig;
use doc\model\ApiDocController;
use doc\model\ApiDocMethod;
use doc\model\ApiDocParam;

/**
 * @package doc\doc_json
 * @author blowsnow
 * @createdate 2020/9/9 10:34
 * @describe
 */
class SwaggerDocJson extends DocJson
{

    public function build($json)
    {
        $paths = [];
        $tags = [];
        /** @var ApiDocController $item */
        foreach ($json['items'] as $item){
            /** @var ApiDocMethod $method */
            foreach ($item->methods as $method){
                $paths[$method->url] = $this->buildMethod($item,$method);
            }
            $tags[] = $item->title;
        }





        /** @var DocConfig $global_config */
        $global_config = $json['global_config'];
        $data = [
            'info' => [
                'title' => $global_config->title,
                'description' => $global_config->description,
                'version' => $global_config->version
            ],
            'swagger' => '2.0',
            'paths' => $paths,
            'tags' => $tags
        ];

        return $data;
    }



    private function buildMethod(ApiDocController $controller,ApiDocMethod $item){
        $items = [];
        foreach ($item->querys as $param){
            $items[] = $this->buildQuery($param);
        }
        foreach ($item->params as $param){
            $items[] = $this->buildParam($param);
        }


        return [
            strtolower($item->method) => [
                "consumes" => [
                    'application/json'
                ],
                'summary' => $item->title,
                'description' => $item->desc,
                'tags' => [
                    $controller->title
                ],
                'parameters' => $items
            ]
        ];
    }

    private function buildParam(ApiDocParam $param){
        return [
            "name" => $param->title,
            "type" => $param->type,
            "required" => $param->require,
            "default" => '',
            "description" => $param->desc,
            "format" => "",
            "in" => "formData"
        ];
    }

    private function buildQuery(ApiDocParam $param){
        return [
            "name" => $param->title,
            "type" => $param->type?:'string',
            "required" => $param->require,
            "default" => '',
            "description" => $param->desc,
            "format" => "",
            "in" => "query"
        ];
    }
}
