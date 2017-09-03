<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App;





$mw1 = function ($request, $response, $next) {
    $response->getBody()->write('middleware 1  start <br>');
    $response = $next($request, $response);
    $response->getBody()->write('middleware 1  end <br>');
    return $response;
};


$mw2 = function ($request, $response, $next) {
    $response->getBody()->write('middleware 2  start <br>');
    $response = $next($request, $response);
    $response->getBody()->write('middleware 2  end <br>');
    return $response;
};


$app->add($mw1);
$app->add($mw2);

$app->get('/mw', function ($request, $response) {
    $response->getBody()->write(' Hello <br>');
    return $response;
})->add($mw1)->add($mw2);


$app->get('/', function ($request, $response) {
    $response->getBody()->write("Hello, world <br>");

    return $response;
});


$app->get('/add/{id}/{name}', function($req, $res, $args) {
	$res->getBody()->write('add'. $args['id']. $args['name']);
	
	return $res;
});






$app->run();
