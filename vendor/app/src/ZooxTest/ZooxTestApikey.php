<?php

namespace ZooxTest;

class ZooxTestApikey
{
    private $cursor;
    private $client;

    public function findApiKey($app, $token)
    {
        $zooxcount = $this->cursor->countDocuments(["app"=>$app]);

        if($zooxcount > 0) {

            $zooxlist = $this->cursor->findOne(["app"=>$app]);

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

        $this->cursor = $this->client
            ->selectDatabase('zoox_mongodb')
            ->selectCollection('zoox_mongodb_collection_apikey');

    }

}
