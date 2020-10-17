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

            return ['msgSuccess' => 'Documento apagado com sucesso'];

        } else {

            return ['msgError' => 'Não foi possivel apagar o documento'];

        }
    }

    public function dataUpdate($data, $data1, $data2)
    {
        $data2 = strtoupper($data2);

        if(strlen($data1) <= 3 || is_numeric($data1)) {
            return ['msgError' => 'Nome Inválido'];
        }

        if(strlen($data2) != 2 || is_numeric($data2)) {
            return ['msgError' => 'Sigla Inválida'];
        }

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

            return ['msgSuccess' => 'Documento atualizado com sucesso'];

        } else {

            return ['msgError' => 'Não foi possivel atualizar o documento'];

        }
    }

    public function dataInsert($data1, $data2)
    {
        $data2 = strtoupper($data2);

        if(strlen($data1) <= 3 || is_numeric($data1)) {
            return ['msgError' => 'Nome Inválido'];
        }

        if(strlen($data2) != 2 || is_numeric($data2)) {
            return ['msgError' => 'Sigla Inválida'];
        }

        $zooxinsert = $this->cursor->insertOne(
                [
                    "id"=>$this->defineNextDocumentId("id"),
                    "nome"=>"{$data1}",
                    "sigla"=>"{$data2}",
                    "data_criacao"=>$this->setTimeZone(),
                    "data_atualizacao"=>""
                ]);

        if($zooxinsert->getInsertedCount() > 0) {

            return ['msgSuccess' => 'Documento inserido com sucesso'];

        } else {

            return ['msgError' => 'Não foi possivel inserir o documento'];

        }
    }

    public function dataListOrder($data, $type)
    {
        if($type == 'asc') {
            $type = 1;
        }

        if($type == 'desc') {
            $type = -1;
        }

        $zooxlist = $this->cursor->find([],['sort'=>["{$data}" => $type]]);

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

            return $this->arrayDocs;

        } else {

            return ['msgError' => 'Não foi possivel ordenar a lista'];

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

            return $this->arrayDocs;

        } else {

            return ['msgError' => 'Registro não encontrado'];
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

            return $this->arrayDocs;

        } else {

            return ['msgError' => 'Nenhum registro encontrado'];

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

            return $this->arrayDocs;

        } else {

            return ['msgError' => 'Busca não encontrada'];

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