<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once('../vendor/autoload.php');

const TEMPLATE = 'default';


$configuration = [
 'settings' => [
 'displayErrorDetails' => true,
 ],
];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);




//$app = new \Slim\App();
$container = $app->getContainer();

$session_login = Session::get('login');




$container['view'] = function($c) use ($session_login) {
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
	$view->getEnvironment()->addGlobal('login', $session_login);
	return $view;
};


$app->get('/',  			'Index:index');
$app->get('/captcha',		'Index:captcha');
$app->get('/logout',		'Index:logout');
$app->any('/login',			'Index:login');

$app->any('/regist',			'Index:regist');
$app->post('/checkLoginName',	'Index:hasSameUser');

$app->get('/admin', 		 Admin::class.':index');
$app->get('/admin/install',  Admin::class.':install');
$app->get('/admin/userlist', Admin::class.':userlist');



$app->post('/article/save', 				Article::class.':save');
$app->get ('/article/list',					Article::class.':list');
$app->get ('/article/{articleid}',			Article::class.':index');
$app->get ('/article/edit/[{articleid}]', 	Article::class.':edit');



$app->group('/user', function() use ($app) {
	$app->get ('/',								User::class.':index');
	$app->get ('/article/edit/[{articleid}]', 	User::class.':editArticle');
	$app->post('/article/save', 				User::class.':saveArticle');

})->add(function($request, $response, $next) use ($session_login) {
	if ($session_login) {
		return $next($request, $response);
	} else {
		return $response->withStatus(302)->withHeader('Location', '/login');
	}
});


// $app->group('/utils', function () use ($app) {
//     $app->get('/date', function ($request, $response) {
//         return $response->getBody()->write(date('Y-m-d H:i:s'));
//     });
//     $app->get('/time', function ($request, $response) {
//         return $response->getBody()->write(time());
//     });
// })->add(function ($request, $response, $next) {
//     $response->getBody()->write('It is now ');
//     $response = $next($request, $response);
//     $response->getBody()->write('. Enjoy!');

//     return $response;
// });



$app->run();
