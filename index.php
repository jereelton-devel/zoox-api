<?php

require __DIR__ . "/vendor/autoload.php";

use \Slim\Slim;
use \ZooxTest\ZooxTestDataHandler;
use \ZooxTest\ZooxTestLogger;
use \ZooxTest\ZooxTestAuth;
use \ZooxTest\ZooxTestApikey;

$app = new Slim();

$checkAuthentication = function($app) {

    $auth = new ZooxTestAuth();
    if($auth->findAppAuth($app) == false) {

        $logger = new ZooxTestLogger();
        $logger->dataLogInsert(json_encode(["action"=>"Authentication", "data"=>"Error $app Nao Autenticada"]));

        echo json_encode(["msgError"=>"Nao Autenticado"]);
        die;
    }
};

$checkAuthorization = function($app, $token) {

    $authorization = new ZooxTestApikey();
    if($authorization->findApiKey($app, $token) == false) {

        $logger = new ZooxTestLogger();
        $logger->dataLogInsert(json_encode(["action"=>"Authorization", "data"=>"Error $app Nao Autorizada"]));

        echo json_encode(["msgError"=>"Nao Autorizado"]);
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
        echo "<h1>ZOOX-API-LOCAL::Acesso Restrito</h1>";
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
        echo false;
        die;
    }

    $checkAuthentication($appAuth);
    $checkAuthorization($appAuth, $xApiKey);

    $logger = new ZooxTestLogger();
    $logger->dataLogInsert(json_encode(["action"=>"ControllAccess", "client"=>"Header[$method]", "data"=>"$appAuth Autenticada e Autorizada"]));

};

$app->get('/action/search/:col/:data', $controllAccess, function($col, $data) {

    $search = new ZooxTestDataHandler($col);
    $response = $search->dataSearch($data);

    $logger = new ZooxTestLogger();
    $logger->dataLogInsert(json_encode(["action"=>"Search", "data"=>"$response"]));

    echo $response;

});

$app->get('/action/list/:col', $controllAccess, function($col) {

    $search = new ZooxTestDataHandler($col);
    $response = $search->dataList();

    $logger = new ZooxTestLogger();
    $logger->dataLogInsert(json_encode(["action"=>"List", "data"=>"$response"]));

    echo $response;

});

$app->get('/action/listone/:col/:data', $controllAccess, function($col, $data) {

    $search = new ZooxTestDataHandler($col);
    $response = $search->dataListOne($data);

    $logger = new ZooxTestLogger();
    $logger->dataLogInsert(json_encode(["action"=>"ListOne", "data"=>"$response"]));

    echo $response;

});

$app->get('/action/listorder/:col/:data/:type', $controllAccess, function($col, $data, $type) {

    $search = new ZooxTestDataHandler($col);
    $response = $search->dataListOrder($data, $type);

    $logger = new ZooxTestLogger();
    $logger->dataLogInsert(json_encode(["action"=>"ListOrder", "data"=>"$response"]));

    echo $response;

});

$app->post('/action/insert/:col/:data1/:data2', $controllAccess, function($col, $data1, $data2) {

    $search = new ZooxTestDataHandler($col);
    $response = $search->dataInsert($data1, $data2);

    $logger = new ZooxTestLogger();
    $logger->dataLogInsert(json_encode(["action"=>"Insert", "data"=>"$response"]));

    echo $response;

});

$app->post('/action/update/:col/:data/:data1/:data2', $controllAccess, function($col, $data, $data1, $data2) {

    $search = new ZooxTestDataHandler($col);
    $response = $search->dataUpdate($data, $data1, $data2);

    $logger = new ZooxTestLogger();
    $logger->dataLogInsert(json_encode(["action"=>"Update", "data"=>"$response"]));

    echo $response;

});

$app->post('/action/delete/:col/:data', $controllAccess, function($col, $data) {

    $search = new ZooxTestDataHandler($col);
    $response = $search->dataDelete($data);

    $logger = new ZooxTestLogger();
    $logger->dataLogInsert(json_encode(["action"=>"Remove", "data"=>"$response"]));

    echo $response;

});

$app->get('/', function() {//Welcome
    echo "<h1>ZOOX-API-LOCAL::Acesso Restrito</h1>";
});

$app->run();

?>