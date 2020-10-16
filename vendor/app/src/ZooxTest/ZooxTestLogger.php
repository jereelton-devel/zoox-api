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

    public function dataLogInsert($data)
    {
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
