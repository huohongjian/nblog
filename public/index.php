<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require '../vendor/autoload.php';


$app = new \Slim\App();

$container = $app->getContainer();
$container['view'] = function($c) {
	    $view = new \Slim\Views\Twig('../templates', [
        'cache' => false
    ]);
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));
    return $view;
};


$app->get('/',  'Index::method1');
$app->get('/admin', 'Admin:home'); //or $app->get('/admin', Admin::class . ':home');


/*
$app->get('/admin', function($request, $response, $args) {
	$ad = new Admin($container);
	$ad->getName($request, $response, $args);
});
*/

$app->get('/add/{id}/{name}', function($req, $res, $args) {
	$res->getBody()->write('add'. $args['id']. $args['name']);

	return $res;
});

$app->get('/hello/{name}', function($request, $response, $args) {
//	print_r($this);
	$a = \Slim\Container::get('view');
	print_r($a instanceof \Slim\Container);
	
	return $a->render($response, 'profile.html', [
	'name' => $args['name']
	]);
})->setName('profile');






$app->run();
