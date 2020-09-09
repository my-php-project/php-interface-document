<?php

/**
 * @author blowsnow
 * @createdate 2020/9/8 15:59
 * @describe
 */
define("DOC_PATH",__DIR__ );
require_once DOC_PATH . "/DocClassLoader.php";

spl_autoload_register('doc\\DocClassLoader::loadClass');
$docConfig = new \doc\DocConfig();

if (isset($_GET['act']) && $_GET['act'] == 'api'){
    $docConfig->base_path = __DIR__ . '/../../application/api/';
    $docBuilder = new \doc\DocBuilder($docConfig);
    $json = $docBuilder->build();

    if (isset($_GET['json'])){
        $jsonName = $_GET['json'];
        $jsonClass = '\doc\doc_json\\' . $jsonName;
        $json = (new $jsonClass())->build($json);
    }

    echo json_encode($json);
}else{
    echo file_get_contents(DOC_PATH."/view/" . $docConfig->view . "/index.html");
}


