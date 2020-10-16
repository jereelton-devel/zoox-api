<?php

namespace ZooxTest;

class ZooxTestApikey
{
    private $client;
    private $collection;
    private $arrayDocs;

    public function findApiKey($app, $token)
    {
        $zooxcount = $this->client
            ->selectDatabase('zoox_mongodb')
            ->selectCollection('zoox_mongodb_collection_'.$this->collection)
            ->countDocuments(["app"=>$app]);

        if($zooxcount > 0) {

            $zooxlist = $this->client
                ->selectDatabase('zoox_mongodb')
                ->selectCollection('zoox_mongodb_collection_' . $this->collection)
                ->findOne(["app"=>$app]);

            if($zooxlist["app"] == $app && $zooxlist["token"] == $token && $zooxlist["timelife"] == 1) {
                return true;
            } else {
                return false;
            }

        } else {

            return false;
        }
    }

    public function __construct()
    {
        $this->client = new \MongoDB\Client(
            'mongodb://localhost:27017'
        );

        $this->collection = "apikey";

    }

}
