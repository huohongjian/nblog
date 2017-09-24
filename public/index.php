<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once('../vendor/autoload.php');

$session = Session::all();


$configuration = [
 'settings' => [
 'displayErrorDetails' => true,
 ],
];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);




//$app = new \Slim\App();
$container = $app->getContainer();


$container['view'] = function($c) use ($session) {
	$view = new \Slim\Views\Twig('../views/default', [
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

	$scheme = 'https';
	$baseUrl = $scheme."://".$host.$port.$path;
	
//	echo dirname($_SERVER['PHP_SELF']) . '/';
	$view->getEnvironment()->addGlobal('baseURL', $baseUrl);
	$view->getEnvironment()->addGlobal('login', $session->login);
	return $view;
};


$app->get('/',  				'Index:index');
$app->get('/captcha',			'Index:captcha');
$app->get('/logout',			'Index:logout');
$app->any('/login',				'Index:login');
$app->any('/regist',			'Index:regist');
$app->post('/checkLoginName',	'Index:hasSameUser');
$app->any('/suggest',			'Index:suggest');




$app->get ('/article/{articleid}',			Article::class.':index');



$app->group('/user', function() use ($app) {
	$app->get ('',							User::class.':index');

	$app->group('/article', function() use ($app) {
		$app->get ('',						User::class.':index');
		$app->get ('/[{category}]',			User::class.':index');
		$app->get ('/edit/[{articleid}]', 	User::class.':editArticle');
		$app->post('/save', 				User::class.':saveArticle');

	});
	$app->group('/manage', function() use ($app) {
		$app->get('',					UserManage::class.':index');
		$app->post('/renewpassword',	UserManage::class.':renewpassword');
		$app->any('/userinfo',			UserManage::class.':userinfo');
		$app->get('/template',			UserManage::class.':template');
		$app->get('/articles',			UserManage::class.':articles');
		$app->get('/category',			UserManage::class.':category');
	});
})->add(function($request, $response, $next) use ($session) {
	if (!$session) {
		return	$response->withStatus(302)->withHeader('Location', '/login');
	}
	$response = $next($request, $response);
	return $response;
});


$app->get('/admin', 		 Admin::class.':index');
$app->get('/admin/install',  Admin::class.':install');
$app->get('/admin/userlist', Admin::class.':userlist');


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
