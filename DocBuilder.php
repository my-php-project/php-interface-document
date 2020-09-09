<?php
namespace doc;
use doc\doc_comment_parse\DocCommentParse;
use doc\doc_class_parse\DocClassParse;
use doc\model\ApiDocMethod;
use doc\model\ApiDocController;
use doc\model\ApiDocModel;

/**
 * @author blowsnow
 * @createdate 2020/9/8 15:46
 * @describe
 */
class DocBuilder
{
    private $docConfig;

    /**
     * Doc constructor.
     * @param DocConfig $docConfig
     */
    public function __construct(DocConfig $docConfig)
    {
        $this->docConfig = $docConfig;
        if ($this->docConfig->base_path == '' || !count($this->docConfig->base_path)){
            throw new \RuntimeException("未配置扫描路径");
        }
    }

    public function build(){
        //判断是否有缓存
        if ($this->docConfig->cache && file_exists(__DIR__ . "/api.json")){
            $json = json_decode(file_get_contents(__DIR__ . "/api.json"),true);
        }else{
            $files = DocScanner::scan($this->docConfig->base_path);

            /** @var DocClassParse $docClassParse */
            $docClassParse = new $this->docConfig->doc_class_parse();
            /** @var DocCommentParse $docCommentParse */
            $docCommentParse = new $this->docConfig->doc_comment_parse();

            $apiDocControllers = [];
            foreach ($files as $file){
                include_once $file;
                $class = $docClassParse->get_class($file);
                $class = new \ReflectionClass($class);

                $apiDocController = $docCommentParse->parse_controller($class->getDocComment());
                $apiDocMethods = [];
                foreach ($class->getMethods() as $method){
                    $url = $docClassParse->parse_url($class,$method);
                    $apiDocMethods[] = $docCommentParse->parse_method($method->getDocComment(),$url);
                }

                $apiDocController->methods = $apiDocMethods;
                $apiDocControllers[] = $apiDocController;
            }



            usort($apiDocControllers,[$this,'compare_weight']);
            foreach ($apiDocControllers as &$apiDocController){
                usort($apiDocController->methods,[$this,'compare_weight']);
                /** @var ApiDocMethod $method */
                foreach ($apiDocController->methods as &$method){
                    usort($method->params,[$this,'compare_weight']);
                }
            }



            $json = [
                "items" => $apiDocControllers,
                "global_config" => $this->docConfig,
                "create_time" => date("Y-m-d H:i:s")
            ];

            file_put_contents(__DIR__ . "/api.json",json_encode($json));
        }

        return $json;
    }

    private function compare_weight(ApiDocModel $a,ApiDocModel $b){
        if($a->weight < $b->weight){
            return -1;
        }else if($a->weight > $b->weight){
            return 1;
        }else{
            return 0;
        }
    }
}
