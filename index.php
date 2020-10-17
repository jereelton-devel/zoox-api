<?php

require __DIR__ . "/vendor/autoload.php";

use \Slim\Slim;
use \ZooxTest\ZooxTestDataHandler;
use \ZooxTest\ZooxTestLogger;
use \ZooxTest\ZooxTestAuth;
use \ZooxTest\ZooxTestApikey;
use \ZooxTest\ZooxTestRequest;
use \ZooxTest\ZooxTestResponse;

$app = new Slim();

$checkAuthentication = function($app) {

    $auth = new ZooxTestAuth();
    if($auth->findAppAuth($app) == false) {

        $datalog = [
            "action"=>"Authentication",
            "data"=>"Error $app Nao Autenticada"
        ];
        $logger = new ZooxTestLogger();
        $logger->dataLogCommon($datalog);

        echo ZooxTestResponse::setResponse(["msgError"=>"Nao Autenticado"], "application/json");
        die;
    }
};

$checkAuthorization = function($app, $token) {

    $authorization = new ZooxTestApikey();
    if($authorization->findApiKey($app, $token) == false) {

        $datalog = [
            "action"=>"Authorization",
            "data"=>"Error $app Nao Autorizada"
        ];
        $logger = new ZooxTestLogger();
        $logger->dataLogCommon($datalog);

        echo ZooxTestResponse::setResponse(["msgError"=>"Nao Autorizado"], "application/json");
        die;
    }
};

$controllAccess = function() {

    global $checkAuthentication;
    global $checkAuthorization;

    $headers = apache_request_headers();
    $hostApi = (isset($headers['Host']) && $headers['Host'] != "") ? $headers['Host'] : ''; //GET
    $referer = (isset($headers['Referer']) && $headers['Referer'] != "") ? $headers['Referer'] : ''; //GET
    $origin  = (isset($headers['Origin']) && $headers['Origin'] != "") ? $headers['Origin'] : '';
    $xClient = (isset($headers['x-Client-Origin']) && $headers['x-Client-Origin'] != "") ? $headers['x-Client-Origin'] : '';
    $xApiKey = (isset($headers['x-Api-key']) && $headers['x-Api-key'] != "") ? $headers['x-Api-key'] : '';

    if($xApiKey == "" || ($hostApi == "" && $referer == "" && $origin == "" && $xClient == "")) {
        echo ZooxTestResponse::setResponse(["msgError"=>"ZOOX-API-LOCAL::Acesso Restrito"], "application/json");
        die;
    }

    if($xClient != "") {
        $appAuth = $xClient; $method = "x-Client-Origin";
    } elseif($origin != "") {
        $appAuth = $origin; $method = "Origin";
    } elseif($referer != "") {
        $appAuth = $referer; $method = "Referer";
    } elseif($hostApi != "") {
        $appAuth = $hostApi; $method = "Host";
    } else {
        echo ZooxTestResponse::setResponse(["msgError"=>"false"], "application/json");
        die;
    }

    $checkAuthentication($appAuth);
    $checkAuthorization($appAuth, $xApiKey);

    $datalog = [
        "action"=>"ControllAccess",
        "client"=>"Header[$method]",
        "data"=>"$appAuth Autenticada e Autorizada"
    ];
    $logger = new ZooxTestLogger();
    $logger->dataLogCommon($datalog);

};

$app->get('/action/list/:datainput', $controllAccess, function($datainput) {

    $getDataInput = ZooxTestRequest::getJsonRequest($datainput);

    if(isset($getDataInput['msgError']) && $getDataInput['msgError'] != "") {
        echo ZooxTestResponse::setResponse($getDataInput, "application/json");
        exit;
    }

    $search = new ZooxTestDataHandler($getDataInput['collection']);
    $response = $search->dataList();

    $logger = new ZooxTestLogger();
    $logger->dataLogInsert("list", $getDataInput, $response);

    echo ZooxTestResponse::setResponse($response, "application/json");

});

$app->get('/action/listone/:datainput', $controllAccess, function($datainput) {

    $getDataInput = ZooxTestRequest::getJsonRequest($datainput);

    if(isset($getDataInput['msgError']) && $getDataInput['msgError'] != "") {
        echo ZooxTestResponse::setResponse($getDataInput, "application/json");
        exit;
    }

    $search = new ZooxTestDataHandler($getDataInput['collection']);
    $response = $search->dataListOne($getDataInput['id']);

    $logger = new ZooxTestLogger();
    $logger->dataLogInsert("listone", $getDataInput, $response);

    echo ZooxTestResponse::setResponse($response, "application/json");

});

$app->get('/action/listorder/:datainput', $controllAccess, function($datainput) {

    $getDataInput = ZooxTestRequest::getJsonRequest($datainput);

    if(isset($getDataInput['msgError']) && $getDataInput['msgError'] != "") {
        echo ZooxTestResponse::setResponse($getDataInput, "application/json");
        exit;
    }

    $search = new ZooxTestDataHandler($getDataInput['collection']);
    $response = $search->dataListOrder($getDataInput['order'], $getDataInput['type']);

    $logger = new ZooxTestLogger();
    $logger->dataLogInsert("listorder", $getDataInput, $response);

    echo ZooxTestResponse::setResponse($response, "application/json");

});

$app->get('/action/search/:datainput', $controllAccess, function($datainput) {

    $getDataInput = ZooxTestRequest::getJsonRequest($datainput);

    if(isset($getDataInput['msgError']) && $getDataInput['msgError'] != "") {
        echo ZooxTestResponse::setResponse($getDataInput, "application/json");
        exit;
    }

    $search = new ZooxTestDataHandler($getDataInput['collection']);
    $response = $search->dataSearch($getDataInput['data']);

    $logger = new ZooxTestLogger();
    $logger->dataLogInsert("search", $getDataInput, $response);

    echo ZooxTestResponse::setResponse($response, "application/json");

});

$app->post('/action/insert/:datainput', $controllAccess, function($datainput) {

    $getDataInput = ZooxTestRequest::getJsonRequest($datainput);

    if(isset($getDataInput['msgError']) && $getDataInput['msgError'] != "") {
        echo ZooxTestResponse::setResponse($getDataInput, "application/json");
        exit;
    }

    $search = new ZooxTestDataHandler($getDataInput['collection']);
    $response = $search->dataInsert($getDataInput['name'], $getDataInput['sigla']);

    $logger = new ZooxTestLogger();
    $logger->dataLogInsert("insert", $getDataInput, $response);

    echo ZooxTestResponse::setResponse($response, "application/json");

});

$app->post('/action/update/:datainput', $controllAccess, function($datainput) {

    $getDataInput = ZooxTestRequest::getJsonRequest($datainput);

    if(isset($getDataInput['msgError']) && $getDataInput['msgError'] != "") {
        echo ZooxTestResponse::setResponse($getDataInput, "application/json");
        exit;
    }

    $search = new ZooxTestDataHandler($getDataInput['collection']);
    $response = $search->dataUpdate($getDataInput['id'], $getDataInput['name'], $getDataInput['sigla']);

    $logger = new ZooxTestLogger();
    $logger->dataLogInsert("update", $getDataInput, $response);

    echo ZooxTestResponse::setResponse($response, "application/json");

});

$app->post('/action/delete/:datainput', $controllAccess, function($datainput) {

    $getDataInput = ZooxTestRequest::getJsonRequest($datainput);

    if(isset($getDataInput['msgError']) && $getDataInput['msgError'] != "") {
        echo ZooxTestResponse::setResponse($getDataInput, "application/json");
        exit;
    }

    $search = new ZooxTestDataHandler($getDataInput['collection']);
    $response = $search->dataDelete($getDataInput['id']);

    $logger = new ZooxTestLogger();
    $logger->dataLogInsert("delete", $getDataInput, $response);

    echo ZooxTestResponse::setResponse($response, "application/json");

});

$app->get('/', function() {//Welcome
    echo "<h1>ZOOX-API-LOCAL::Acesso Restrito</h1>";
});

$app->run();

?>