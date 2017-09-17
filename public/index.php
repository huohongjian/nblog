<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once('../vendor/autoload.php');


$app = new \Slim\App();
$container = $app->getContainer();


$container['view'] = function($c) {
	$view = new \Slim\Views\Twig('../views', [
		'cache' => false
	]);
	// Instantiate and add Slim specific extension
	$basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
	$view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

	$request = $c->request;
	$uri 	 = $request->getUri();
	$scheme  = $uri->getScheme();
	$host 	 = $uri->getHost();
	$path 	 = $uri->getBasePath();
	$port 	 = $uri->getPort() ;
	if ($port) $port = ':'.$port;
	$baseUrl = $scheme."://".$host.$port.$path;
//	$baseUrl = "https://".$host.$port.$path;
//	echo $scheme, $port;

//	echo dirname($_SERVER['PHP_SELF']) . '/';
	$view->getEnvironment()->addGlobal('baseURL', $baseUrl);

	$login = Session::get('login');
	$view->getEnvironment()->addGlobal('login', $login);
	return $view;
};


$app->get('/',  			'Index:index');
$app->get('/captcha',		'Index:captcha');
$app->get('/logout',		'Index:logout');
$app->any('/login',			'Index:login');

$app->any('/regist',		'Index:regist');
$app->get('/hasSameUser/[{login}]',	'Index:hasSameUser');

$app->get('/admin', 		 Admin::class.':index');
$app->get('/admin/install',  Admin::class.':install');
$app->get('/admin/userlist', Admin::class.':userlist');

$app->get('/user',	User::class.':index');



$app->post('/article/save', 				Article::class.':save');
$app->get ('/article/list',					Article::class.':list');
$app->get ('/article/{articleid}',			Article::class.':index');
$app->get ('/article/edit/[{articleid}]', 	Article::class.':edit');



$app->run();
