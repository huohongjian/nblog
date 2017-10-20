<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once('../vendor/autoload.php');


$configuration = [
	'settings' => [
	'displayErrorDetails' => true,
	],
];

$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);




//$app = new \Slim\App();
$container = $app->getContainer();


$container['view'] = function($c) {
	$view = new \Slim\Views\Twig('../views/default', [
		'cache' => false
	]);
	// Instantiate and add Slim specific extension
	$basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
	$view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));


	// $request = $c->request;
	// $uri 	 = $request->getUri();
	// $scheme  = $uri->getScheme();
	// $host 	 = $uri->getHost();
	// $path 	 = $uri->getBasePath();
	// $port 	 = $uri->getPort() ;
	// if ($port) $port = ':'.$port;

	// $scheme = 'https';
	// $baseUrl = $scheme."://".$host.$port.$path;

	$url = "https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	$baseUrl = strstr($url, '/index.php', true);

	$view->getEnvironment()->addGlobal('baseURL', $baseUrl);
	$view->getEnvironment()->addGlobal('USER',   Session::get());
	return $view;
};


$app->get('/',  				'Index:index');
$app->get('/captcha',			'Index:captcha');
$app->get('/logout',			'Index:logout');
$app->any('/login',				'Index:login');
$app->any('/regist',			'Index:regist');
$app->post('/checkLoginName',	'Index:hasSameUser');
$app->any('/suggest',			'Index:suggest');
$app->any('/donation',			'Index:donation');

$app->any('/book[/{articleid}]',	'Index:book');
$app->any('/article/{articleid}',	'Index:article');
$app->any('/category[/{key}]',		'Index:category');
$app->any('/search',				'Index:search');



$app->group('/user', function() use ($app) {
	$app->any('',						User::class.':index');
	$app->any('/edit/[{articleid}]', 	User::class.':edit');

	$app->group('/manage', function() use ($app) {
		$app->get('/',					UserManage::class.':index');
		$app->any('/userinfo',			UserManage::class.':userinfo');
		$app->any('/template',			UserManage::class.':template');
		$app->any('/articles',			UserManage::class.':articles');
		$app->any('/category',			UserManage::class.':category');
	});
})->add(function($request, $response, $next) {
	if (empty(Session::get('login'))) {
		return $response->withStatus(302)->withHeader('Location', '/login');
	}
	$response = $next($request, $response);
	return $response;
});



$app->group('/admin', function() use ($app) {
	$app->get('/', 				Admin::class.':index');
	$app->get('/install',  		Admin::class.':install');
	$app->get('/users', 		Admin::class.':users');
	$app->any('/articles', 		Admin::class.':articles');
	$app->any('/categories', 	Admin::class.':categories');
	$app->any('/donations', 	Admin::class.':donations');
	$app->any('/homepage',		Admin::class.':homepage');

})->add(function($request, $response, $next) {
	if (empty(Session::get('roleid')) or Session::get('roleid')>2) {
		$response->getBody()->write('
			请以管理员身份登录！
			<a href="/">首页</a>
			<a href="/login">登录</a>'
		);
		return $response;
	}
	$response = $next($request, $response);
	return $response;
});



$app->run();
