<?php

//Slim is a PHP micro framework that helps you quickly write simple yet powerful web applications and APIs

require __DIR__ . "/vendor/autoload.php";

use \Slim\Slim;
use \MongoDB\Client;

$app = new Slim();

date_default_timezone_set("America/Sao_Paulo");

$headers = apache_request_headers();
//$origin  = $headers['Origin'];
/*echo '<pre>';
var_dump($headers);
echo '</pre>';*/

$app->get('/', function(){
	echo "<h1>ZOOX-API</h1>";

    $client = new Client(
        'mongodb://localhost:27017'
    );

    //Atualizar
    /*$zooxupdate = $client->selectDatabase('zoox_mongodb')->selectCollection('zoox_mongodb_collection')->updateOne(["id"=>1],['$set'=>["nome"=>"Amazonas"]]);

    echo $zooxupdate->getModifiedCount();*/


    //Contar Especifico
    $zooxcount = $client->selectDatabase('zoox_mongodb')->selectCollection('zoox_mongodb_collection')->countDocuments(["nome"=>"Rio de Janeiro"]);
    //Contar Todos
    $zooxcount = $client->selectDatabase('zoox_mongodb')->selectCollection('zoox_mongodb_collection')->countDocuments();

    //Listar
    $zooxlist = $client->selectDatabase('zoox_mongodb')->selectCollection('zoox_mongodb_collection')->find();

    echo $zooxcount;

    echo '<pre>';
    foreach ($zooxlist as $document) {
        echo $document["id"] .' - '. $document["nome"] . "\n";
    }
    echo '</pre>';



    //Inserir
    /*$zoox = $client->selectDatabase('zoox_mongodb')->selectCollection('zoox_mongodb_collection')->insertOne(["id"=>3,"nome"=>"Rio de Janeiro","sigla"=>"RJ","data_criacao"=>"10/10/2020","data_atualizacao"=>"14/10/2020"]);*/



    //Remover
    //$zoox = $client->selectDatabase('zoox_mongodb')->selectCollection('zoox_mongodb_collection')->deleteOne(["id"=>4]);



    /*echo '<pre>';
    var_dump($zoox);
    echo '</pre>';

    echo '<pre>';
    var_dump($data);
    echo '</pre>';*/

});

//$app->post();

//$app->put();

//$app->delete();

$app->run();

?>