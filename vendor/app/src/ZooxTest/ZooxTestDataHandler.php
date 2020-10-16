<?php

namespace ZooxTest;

class ZooxTestDataHandler
{
    private $cursor;
    private $client;
    private $arrayDocs;
    private $dateCurrent;
    private $currentIndex;

    private function setTimeZone()
    {
        date_default_timezone_set("America/Sao_Paulo");
        $this->dateCurrent = date('d/m/Y H:i:s', time());

        return $this->dateCurrent;
    }

    private function defineNextDocumentId($index)
    {
        $zooxlist = $this->cursor->find([],['limit'=>1,'sort'=>["{$index}" => -1]]); //Ascendente

        foreach ($zooxlist as $document) {
            $this->currentIndex = $document["id"];
        }

        return $this->currentIndex + 1;
    }

    public function dataDelete($data)
    {
        $zooxdelete = $this->cursor->deleteOne(["id"=>intval($data)]);

        if($zooxdelete->getDeletedCount()) {

            return json_encode(['msgSuccess' => 'Documento apagado com sucesso']);

        } else {

            return json_encode(['msgError' => 'Não foi possivel apagar o documento'], JSON_UNESCAPED_UNICODE);

        }
    }

    public function dataUpdate($data, $data1, $data2)
    {
        $data2 = strtoupper($data2);

        $zooxupdate = $this->cursor->updateOne(
                ["id"=>intval($data)],
                ['$set'=>
                    [
                        "nome"=>"{$data1}",
                        "sigla"=>"{$data2}",
                        "data_atualizacao"=>$this->setTimeZone()
                    ]
                ]);

        if($zooxupdate->getModifiedCount() > 0 || $zooxupdate->getMatchedCount()) {

            return json_encode(['msgSuccess' => 'Documento atualizado com sucesso']);

        } else {

            return json_encode(['msgError' => 'Não foi possivel atualizar o documento'], JSON_UNESCAPED_UNICODE);

        }
    }

    public function dataInsert($data1, $data2)
    {
        $data2 = strtoupper($data2);

        $zooxinsert = $this->cursor->insertOne(
                [
                    "id"=>$this->defineNextDocumentId("id"),
                    "nome"=>"{$data1}",
                    "sigla"=>"{$data2}",
                    "data_criacao"=>$this->setTimeZone(),
                    "data_atualizacao"=>""
                ]);

        if($zooxinsert->getInsertedCount() > 0) {

            return json_encode(['msgSuccess' => 'Documento inserido com sucesso']);

        } else {

            return json_encode(['msgError' => 'Não foi possivel inserir o documento'], JSON_UNESCAPED_UNICODE);

        }
    }

    public function dataListOrder($data)
    {
        $zooxlist = $this->cursor->find([],['sort'=>["{$data}" => 1]]); //Ascendente

        foreach ($zooxlist as $document) {

            $this->arrayDocs[] = [
                "id" => $document["id"],
                "nome" => $document["nome"],
                "sigla" => $document["sigla"],
                "data_criacao" => $document["data_criacao"],
                "data_atualizacao" => $document["data_atualizacao"]
            ];
        }

        if(count($this->arrayDocs) > 0) {

            return json_encode($this->arrayDocs);

        } else {

            return json_encode(['msgError' => 'Não foi possivel ordenar a lista'], JSON_UNESCAPED_UNICODE);

        }
    }

    public function dataListOne($data)
    {
        $zooxcount = $this->cursor->countDocuments(["id"=>intval($data)]);

        if($zooxcount > 0) {

            $zooxlist = $this->cursor->findOne(["id" => intval($data)]);

            $this->arrayDocs[] = [
                "id" => $zooxlist["id"],
                "nome" => $zooxlist["nome"],
                "sigla" => $zooxlist["sigla"],
                "data_criacao" => $zooxlist["data_criacao"],
                "data_atualizacao" => $zooxlist["data_atualizacao"]
            ];

            return json_encode($this->arrayDocs);

        } else {

            return json_encode(['msgError' => 'Registro não encontrado'], JSON_UNESCAPED_UNICODE);
        }
    }

    public function dataList()
    {
        $zooxcount = $this->cursor->countDocuments();

        if($zooxcount > 0) {

            $zooxlist = $this->cursor->find();

            foreach ($zooxlist as $document) {

                $this->arrayDocs[] = [
                    "id" => $document["id"],
                    "nome" => $document["nome"],
                    "sigla" => $document["sigla"],
                    "data_criacao" => $document["data_criacao"],
                    "data_atualizacao" => $document["data_atualizacao"]
                ];

            }

            return json_encode($this->arrayDocs);

        } else {

            return json_encode(['msgError' => 'Nenhum registro encontrado']);

        }
    }

    public function dataSearch($data)
    {
        $zooxlistNome = $this->cursor->find([
            "nome" => new \MongoDB\BSON\Regex("{$data}", 'i')
        ]);

        $zooxlistSigla = $this->cursor->find([
            "sigla" => new \MongoDB\BSON\Regex("{$data}", 'i')
        ]);

        foreach ($zooxlistNome as $document) {

            $this->arrayDocs[] = [
                "id" => $document["id"],
                "nome" => $document["nome"],
                "sigla" => $document["sigla"],
                "data_criacao" => $document["data_criacao"],
                "data_atualizacao" => $document["data_atualizacao"]
            ];
        }

        foreach ($zooxlistSigla as $document) {

            $this->arrayDocs[] = [
                "id" => $document["id"],
                "nome" => $document["nome"],
                "sigla" => $document["sigla"],
                "data_criacao" => $document["data_criacao"],
                "data_atualizacao" => $document["data_atualizacao"]
            ];
        }

        if(isset($this->arrayDocs) && count($this->arrayDocs) > 0) {

            return json_encode($this->arrayDocs);

        } else {

            return json_encode(['msgError' => 'Busca não encontrada'], JSON_UNESCAPED_UNICODE);

        }
    }
    
    public function __construct($col)
    {
        $this->client = new \MongoDB\Client(
            'mongodb://localhost:27017'
        );

        $this->cursor = $this->client
            ->selectDatabase('zoox_mongodb')
            ->selectCollection('zoox_mongodb_collection_'.$col);
    }
    
}
?>