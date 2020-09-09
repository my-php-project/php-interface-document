<?php
namespace doc\doc_json;

use doc\DocConfig;
use doc\model\ApiDocController;
use doc\model\ApiDocMethod;
use doc\model\ApiDocParam;

/**
 * @author blowsnow
 * @createdate 2020/9/9 9:10
 * @describe postman json转换器
 */
class PostManCollectionV2DocJson extends DocJson
{

    public function build($json)
    {
        $items = [];
        /** @var ApiDocController $item */
        foreach ($json['items'] as $item){
            $items[] = $this->buildController($item);
        }
        /** @var DocConfig $global_config */
        $global_config = $json['global_config'];
        $data = [
            'info' => [
                'name' => $global_config->title,
                'description' => $global_config->description,
                'schema' => "https://schema.getpostman.com/json/collection/v2.0.0/collection.json"
            ],
            'item' => $items,
            'variable' => [
                [
                    'key' => 'URL',
                    'value' => $global_config->base_url
                ]
            ]
        ];

        return $data;
    }


    private function buildController(ApiDocController $item){
        $items = [];
        foreach ($item->methods as $method){
            $items[] = $this->buildMethod($method);
        }
        return [
            "name" => $item->title,
            "description" => $item->desc,
            "item" => $items
        ];
    }

    private function buildMethod(ApiDocMethod $item){
        $items = [];
        foreach ($item->params as $param){
            $items[] = $this->buildParam($param);
        }

        if (count($item->querys)){
            $itemQuerys = [];
            foreach ($item->querys as $param){
                $itemQuerys[] = $this->buildQuery($param);
            }
            $paths = explode("/",$item->url);
            return [
                "name" => $item->title,
                "request" => [
                    "method" => strtoupper($item->method),
                    "description" => $item->desc . PHP_EOL . "" . $item->return,
                    "header" => [],
                    "body" => [
                        "mode" => "formdata",
                        "formdata" => $items,
                    ],
                    "url" => [
                        "raw" => "{{URL}}/".$item->url,
                        "host" => ["{{URL}}"],
                        "path" => $paths,
                        "query" => $itemQuerys
                    ],
                ]
            ];
        }
        return [
            "name" => $item->title,
            "request" => [
                "url" => "{{URL}}/".$item->url,
                "method" => strtoupper($item->method),
                "description" => $item->desc . PHP_EOL . "" . $item->return,
                "header" => [],
                "body" => [
                    "mode" => "formdata",
                    "formdata" => $items,
                ]
            ]
        ];
    }

    private function buildParam(ApiDocParam $param){
        return [
            "key" => $param->name,
            "value" => '',
            "description" => $param->desc,
            "type" => 'text',
        ];
    }

    private function buildQuery(ApiDocParam $param){
        return [
            "key" => $param->name,
            "value" => '',
            "description" => $param->desc
        ];
    }
}
