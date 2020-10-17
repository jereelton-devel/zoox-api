<?php

namespace ZooxTest;

class ZooxTestLogger
{
    private $cursor;
    private $client;
    private $currentIndex;

    private function defineNextDocumentId($index)
    {
        $zooxlist = $this->cursor->find([],['limit'=>1,'sort'=>["{$index}" => -1]]);

        foreach ($zooxlist as $document) {
            $this->currentIndex = $document["id"];
        }

        return $this->currentIndex + 1;
    }

    public function dataLogCommon($data)
    {
        $data = json_encode($data);

        $zooxinsertlog = $this->cursor->insertOne(
            [
                "id"=>$this->defineNextDocumentId("id"),
                "logdetail"=>"{$data}"
            ]);

        if($zooxinsertlog->getInsertedCount() > 0) {

            return json_encode(['msgSuccess' => 'Log inserido com sucesso']);

        } else {

            return json_encode(['msgError' => 'Não foi possivel inserir o log'], JSON_UNESCAPED_UNICODE);

        }
    }

    public function dataLogInsert($type, $arr, $resp)
    {
        $log = "";

        switch ($type) {

            case "list":
                $log = json_encode([
                    "action"=>"List",
                    "collection" => $arr['collection'],
                    "response"=>json_encode($resp)
                ]);
                break;

            case "listone":
                $log = json_encode([
                    "action"=>"ListOne",
                    "collection" => $arr['collection'],
                    "id" => $arr['id'],
                    "response"=>json_encode($resp)
                ]);
                break;

            case "listorder":
                $log = json_encode([
                    "action"=>"ListOrder",
                    "collection" => $arr['collection'],
                    "order" => $arr['order'],
                    "type" => $arr['type'],
                    "response"=>json_encode($resp)
                ]);
                break;

            case "search":
                $log = json_encode([
                    "action"=>"Search",
                    "collection" => $arr['collection'],
                    "data" => $arr['data'],
                    "response"=>json_encode($resp)
                ]);
                break;

            case "insert":
                $log = json_encode([
                    "action"=>"Insert",
                    "collection" => $arr['collection'],
                    "name" => $arr['name'],
                    "sigla" => $arr['sigla'],
                    "response"=>json_encode($resp)
                ]);
                break;

            case "update":
                $log = json_encode([
                    "action"=>"Update",
                    "collection" => $arr['collection'],
                    "id" => $arr['id'],
                    "name" => $arr['name'],
                    "sigla" => $arr['sigla'],
                    "response"=>json_encode($resp)
                ]);
                break;

            case "delete":
                $log = json_encode([
                    "action"=>"Delete",
                    "collection" => $arr['collection'],
                    "id" => $arr['id'],
                    "response"=>json_encode($resp)
                ]);
                break;

            default:
                $log = json_encode(["action"=>"False"]);
        }

        $zooxinsertlog = $this->cursor->insertOne(
                [
                    "id"=>$this->defineNextDocumentId("id"),
                    "logdetail"=>"{$log}"
                ]);

        if($zooxinsertlog->getInsertedCount() > 0) {

            return true;

        } else {

            return false;

        }
    }

    public function __construct()
    {
        $this->client = new \MongoDB\Client(
            'mongodb://localhost:27017'
        );

        $this->cursor = $this->client
            ->selectDatabase('zoox_mongodb')
            ->selectCollection('zoox_mongodb_collection_log');

    }

}
