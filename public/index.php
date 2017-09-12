<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/*
define('SITE_NAME','China FreeBSD');
$the_file_path = $_SERVER['PHP_SELF'];
$findme   = '/index.php';
$pos = strpos($the_file_path, $findme);
$target_path = substr($the_file_path, 0,$pos);
$site_url = "http://".$_SERVER['HTTP_HOST'].$target_path;
define('SITE_URL',$site_url);
*/



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
	echo $scheme, $port;
	$view->getEnvironment()->addGlobal('baseURL', $baseUrl);
	return $view;
};



$app->get('/',  		'Index:index');
$app->get('/regist',	'Index:regist');
$app->any('/login',		'Index:login');

$app->get('/admin', 		 Admin::class.':index');
$app->get('/admin/install',  Admin::class.':install');
$app->get('/admin/userlist', Admin::class.':userlist');

$app->get ('/detail/{articleid}',			Article::class.':display');
$app->get ('/article/list', 				Article::class.':list');
$app->get ('/article/edit/[{articleid}]', 	Article::class.':edit');
$app->post('/article/save', 				Article::class.':save');




//session_start();

// $container['flash'] = function () {
//     return new \Slim\Flash\Messages();
// };

// $app->get('/foo', function ($req, $res, $args) {
//     // Set flash message for next request
//     $this->flash->addMessage('Test', 'This is a message');

//     // Redirect
//     return $res->withStatus(302)->withHeader('Location', '/bar');
// });

// $app->get('/bar', function ($req, $res, $args) {
//     // Get flash messages from previous request
//     $messages = $this->flash->getMessages();
//     print_r($messages);
// });



$app->run();
