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

//Contar Especifico
/*$zooxcount = $client->selectDatabase('zoox_mongodb')->selectCollection('zoox_mongodb_collection')->countDocuments(["nome"=>"Rio de Janeiro"]);*/

//Busca
$app->get('/action/search/:col/:data', function($col, $data) {

    $client = new Client(
        'mongodb://localhost:27017'
    );

    $arrayDocs = [];

    $zooxlistNome = $client
        ->selectDatabase('zoox_mongodb')
        ->selectCollection('zoox_mongodb_collection_'.$col)
        ->find(["nome" => "{$data}"]);

    $zooxlistSigla = $client
        ->selectDatabase('zoox_mongodb')
        ->selectCollection('zoox_mongodb_collection_'.$col)
        ->find(["sigla"=>"{$data}"]);

    foreach ($zooxlistNome as $document) {

        $arrayDocs[] = [
            "id" => $document["id"],
            "nome" => $document["nome"],
            "sigla" => $document["sigla"],
            "data_criacao" => $document["data_criacao"],
            "data_atualizacao" => $document["data_atualizacao"]
        ];
    }

    foreach ($zooxlistSigla as $document) {

        $arrayDocs[] = [
            "id" => $document["id"],
            "nome" => $document["nome"],
            "sigla" => $document["sigla"],
            "data_criacao" => $document["data_criacao"],
            "data_atualizacao" => $document["data_atualizacao"]
        ];
    }

    if(count($arrayDocs) > 0) {

        echo json_encode($arrayDocs);

    } else {

        echo json_encode(['msgError' => 'Não foi possivel ordenar a lista']);

    }

});

//Listar
$app->get('/action/list/:col', function($col) {

    $client = new Client(
        'mongodb://localhost:27017'
    );

    $zooxcount = $client
        ->selectDatabase('zoox_mongodb')
        ->selectCollection('zoox_mongodb_collection_'.$col)
        ->countDocuments();

    if($zooxcount > 0) {

        $zooxlist = $client
            ->selectDatabase('zoox_mongodb')
            ->selectCollection('zoox_mongodb_collection_'.$col)
            ->find();

        foreach ($zooxlist as $document) {

            $arrayDocs[] = [
                "id" => $document["id"],
                "nome" => $document["nome"],
                "sigla" => $document["sigla"],
                "data_criacao" => $document["data_criacao"],
                "data_atualizacao" => $document["data_atualizacao"]
            ];

        }

        echo json_encode($arrayDocs);

    } else {

        echo json_encode(['msgError' => 'Nenhum registro encontrado']);

    }

});

//Listagem Especifica
$app->get('/action/listone/:col/:data', function($col, $data) {

    $client = new Client(
        'mongodb://localhost:27017'
    );

    $zooxcount = $client
        ->selectDatabase('zoox_mongodb')
        ->selectCollection('zoox_mongodb_collection_'.$col)
        ->countDocuments(["id"=>intval($data)]);

    if($zooxcount > 0) {

        $zooxlist = $client
            ->selectDatabase('zoox_mongodb')
            ->selectCollection('zoox_mongodb_collection_' . $col)
            ->findOne(["id" => intval($data)]);

        $arrayDocs[] = [
            "id" => $zooxlist["id"],
            "nome" => $zooxlist["nome"],
            "sigla" => $zooxlist["sigla"],
            "data_criacao" => $zooxlist["data_criacao"],
            "data_atualizacao" => $zooxlist["data_atualizacao"]
        ];

        echo json_encode($arrayDocs);

    } else {

        echo json_encode(['msgError' => 'Registro não encontrado']);
    }

});

//Listagem ordenada
$app->get('/action/listorder/:col/:data', function($col, $data) {

    $client = new Client(
        'mongodb://localhost:27017'
    );

    $zooxlist = $client
        ->selectDatabase('zoox_mongodb')
        ->selectCollection('zoox_mongodb_collection_'.$col)
        ->find([],['sort'=>["{$data}" => 1]]);

    foreach ($zooxlist as $document) {

        $arrayDocs[] = [
            "id" => $document["id"],
            "nome" => $document["nome"],
            "sigla" => $document["sigla"],
            "data_criacao" => $document["data_criacao"],
            "data_atualizacao" => $document["data_atualizacao"]
        ];
    }

    if(count($arrayDocs) > 0) {

        echo json_encode($arrayDocs);

    } else {

        echo json_encode(['msgError' => 'Não foi possivel ordenar a lista']);

    }

});

//Inserir
$app->post('/action/insert/:col/:data1/:data2', function($col, $data1, $data2) {

    $client = new Client(
        'mongodb://localhost:27017'
    );

    $zooxinsert = $client
        ->selectDatabase('zoox_mongodb')
        ->selectCollection('zoox_mongodb_collection_'.$col)
        ->insertOne(
            [
                "id"=>$client
                    ->selectDatabase('zoox_mongodb')
                    ->selectCollection('zoox_mongodb_collection_'.$col)
                    ->estimatedDocumentCount([]) + 1,
                "nome"=>"{$data1}",
                "sigla"=>"{$data2}",
                "data_criacao"=>date("d/m/Y"),
                "data_atualizacao"=>""
            ]);

    if($zooxinsert->getInsertedCount() > 0) {

        echo json_encode(['msgSuccess' => 'Documento inserido com sucesso']);

    } else {

        echo json_encode(['msgError' => 'Não foi possivel inserir o documento']);

    }

});

//Atualizar
$app->post('/action/update/:col/:data/:data1/:data2', function($col, $data, $data1, $data2) {

    $client = new Client(
        'mongodb://localhost:27017'
    );

    $zooxupdate = $client
        ->selectDatabase('zoox_mongodb')
        ->selectCollection('zoox_mongodb_collection_'.$col)
        ->updateOne(
            ["id"=>intval($data)],
            ['$set'=>
                [
                    "nome"=>"{$data1}",
                    "sigla"=>"{$data2}",
                    "data_atualizacao"=>date("d/m/Y")
                ]
            ]);

    if($zooxupdate->getModifiedCount() > 0 || $zooxupdate->getMatchedCount()) {

        echo json_encode(['msgSuccess' => 'Documento atualizado com sucesso']);

    } else {

        echo json_encode(['msgError' => 'Não foi possivel atualizar o documento']);

    }

});

//Remover
$app->post('/action/delete/:col/:data', function($col, $data) {

    $client = new Client(
        'mongodb://localhost:27017'
    );

    $zooxdelete = $client
        ->selectDatabase('zoox_mongodb')
        ->selectCollection('zoox_mongodb_collection_'.$col)
        ->deleteOne(["id"=>intval($data)]);

    if($zooxdelete->getDeletedCount()) {

        echo json_encode(['msgSuccess' => 'Documento apagado com sucesso']);

    } else {

        echo json_encode(['msgError' => 'Não foi possivel apagar o documento']);

    }

});

$app->get('/', function() {//Welcome
    echo "<h1>ZOOX-API-LOCAL::Acesso Restrito</h1>";
});

$app->run();

?>