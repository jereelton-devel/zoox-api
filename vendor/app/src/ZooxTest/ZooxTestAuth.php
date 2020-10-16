<?php

namespace ZooxTest;

class ZooxTestAuth
{
    private $client;
    private $collection;

    public function findAppAuth($app)
    {
        $zooxcount = $this->client
            ->selectDatabase('zoox_mongodb')
            ->selectCollection('zoox_mongodb_collection_'.$this->collection)
            ->countDocuments(["app"=>$app]);

        if($zooxcount > 0) {

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

        $this->collection = "auth";

    }

}
