<?php

//Slim is a PHP micro framework that helps you quickly write simple yet powerful web applications and APIs

require __DIR__ . "/vendor/autoload.php";

use \Slim\Slim;

$app = new Slim();

$app->get('/', function(){
	echo "ZOOX-API";
});

//$app->post();

//$app->put();

//$app->delete();

$app->run();

?>