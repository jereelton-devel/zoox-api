<?php

namespace ZooxTest;

class ZooxTestAuth
{
    private $cursor;
    private $client;

    public function findAppAuth($app)
    {
        $zooxcount = $this->cursor->countDocuments(["app"=>$app]);

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

        $this->cursor = $this->client
            ->selectDatabase('zoox_mongodb')
            ->selectCollection('zoox_mongodb_collection_auth');

    }

}
