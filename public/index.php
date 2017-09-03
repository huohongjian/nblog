<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//echo $_SERVER['HTTP_HOST'].""; #localhost
//echo $_SERVER['PHP_SELF'].""; #/blog/testurl.php


define('SITE_NAME','China FreeBSD');
$the_file_path = $_SERVER['PHP_SELF'];
$findme   = '/index.php';
$pos = strpos($the_file_path, $findme);
$target_path = substr($the_file_path, 0,$pos);
$site_url = "http://".$_SERVER['HTTP_HOST'].$target_path;

define('SITE_URL',$site_url);

//echo SITE_URL;




require_once('../vendor/autoload.php');


$app = new \Slim\App();

$container = $app->getContainer();
$container['view'] = function($c) {
	    $view = new \Slim\Views\Twig('../template', [
        'cache' => false
    ]);
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

$request = $c->request;
    $uri = $request->getUri();
    $scheme= $uri->getScheme();
    $host = $uri->getHost();
    $port = $uri->getPort() ;
    $baseUrl = $scheme."://".$host.":".$port;


    
    $view->getEnvironment()->addGlobal('baseURL', SITE_URL);

echo SITE_URL;

    return $view;
};





$app->get('/',  Index::class .':index');

$app->get('/admin', Admin::class .':index');
$app->get('/admin/install', 'Admin:install');




$app->get('/hello/{name}', function($request, $response, $args) {
//	print_r($this);
	$a = \Slim\Container::get('view');
	print_r($a instanceof \Slim\Container);
	
	return $a->render($response, 'profile.html', [
	'name' => $args['name']
	]);
})->setName('profile');



$app->run();
