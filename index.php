<?php

//Slim is a PHP micro framework that helps you quickly write simple yet powerful web applications and APIs

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
    $origin  = (isset($headers['Origin']) && $headers['Origin'] != "") ? $headers['Origin'] : '';
    $host    = (isset($headers['Host']) && $headers['Host'] != "") ? $headers['Host'] : '';
    $xApiKey = (isset($headers['x-Api-key']) && $headers['x-Api-key'] != "") ? $headers['x-Api-key'] : '';

    /*echo '<pre>';
    var_dump($headers, $origin, $host, $xApiKey);
    echo '</pre>';*/

    if($origin != "") {
        $appAuth = $origin;
    } elseif($host != "") {
        $appAuth = $host;
    } else {
        echo false;
        die;
    }

    if($xApiKey == "") {
        echo "<h1>ZOOX-API-LOCAL::Acesso Restrito</h1>";
        die;
    }

    $checkAuthentication($appAuth);
    $checkAuthorization($appAuth, $xApiKey);

    $logger = new ZooxTestLogger();
    $logger->dataLogInsert(json_encode(["action"=>"ControllAccess", "data"=>"$appAuth Autenticada e Autorizada"]));

};

//Busca - http://zoox.api.local/action/search/cidade/SP (Exemplo)
$app->get('/action/search/:col/:data', $controllAccess, function($col, $data) {

    $search = new ZooxTestDataHandler($col);
    $response = $search->dataSearch($data);

    $logger = new ZooxTestLogger();
    $logger->dataLogInsert(json_encode(["action"=>"Search", "data"=>"$response"]));

    echo $response;

});

//Listar - http://zoox.api.local/action/list/cidade (Exemplo)
$app->get('/action/list/:col', $controllAccess, function($col) {

    $search = new ZooxTestDataHandler($col);
    $response = $search->dataList();

    $logger = new ZooxTestLogger();
    $logger->dataLogInsert(json_encode(["action"=>"List", "data"=>"$response"]));

    echo $response;

});

//Listagem Especifica - http://zoox.api.local/action/listone/cidade/1 (Exemplo)
$app->get('/action/listone/:col/:data', $controllAccess, function($col, $data) {

    $search = new ZooxTestDataHandler($col);
    $response = $search->dataListOne($data);

    $logger = new ZooxTestLogger();
    $logger->dataLogInsert(json_encode(["action"=>"ListOne", "data"=>"$response"]));

    echo $response;

});

//Listagem ordenada - http://zoox.api.local/action/listorder/cidade/nome (Exemplo)
$app->get('/action/listorder/:col/:data', $controllAccess, function($col, $data) {

    $search = new ZooxTestDataHandler($col);
    $response = $search->dataListOrder($data);

    $logger = new ZooxTestLogger();
    $logger->dataLogInsert(json_encode(["action"=>"ListOrder", "data"=>"$response"]));

    echo $response;

});

//Inserir - http://zoox.api.local/action/insert/cidade/Alphaville/SP (Exemplo)
$app->post('/action/insert/:col/:data1/:data2', $controllAccess, function($col, $data1, $data2) {

    $search = new ZooxTestDataHandler($col);
    $response = $search->dataInsert($data1, $data2);

    $logger = new ZooxTestLogger();
    $logger->dataLogInsert(json_encode(["action"=>"Insert", "data"=>"$response"]));

    echo $response;

});

//Atualizar - http://zoox.api.local/action/update/cidade/5/Ubatuba/RJ (Exemplo)
$app->post('/action/update/:col/:data/:data1/:data2', $controllAccess, function($col, $data, $data1, $data2) {

    $search = new ZooxTestDataHandler($col);
    $response = $search->dataUpdate($data, $data1, $data2);

    $logger = new ZooxTestLogger();
    $logger->dataLogInsert(json_encode(["action"=>"Update", "data"=>"$response"]));

    echo $response;

});

//Remover - http://zoox.api.local/action/delete/cidade/9 (Exemplo)
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