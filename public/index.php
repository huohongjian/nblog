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
	$view = new \Slim\Views\Twig('../views/default', array(
		'autoescape' => false,
		'cache' => false
	));
	// Instantiate and add Slim specific extension
	$basePath = rtrim(str_ireplace('index.php', '', $c->request->getUri()->getBasePath()), '/');
	$view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

	$baseUrl = '';
	$view->getEnvironment()->addGlobal('baseURL', $baseUrl);
	$view->getEnvironment()->addGlobal('USER',   Session::get());
	return $view;
};


$app->get('/',  					'IndexController:index');
$app->get('/captcha',				'IndexController:captcha');
$app->get('/logout',				'IndexController:logout');
$app->any('/login',					'IndexController:login');
$app->any('/regist',				'IndexController:regist');
$app->post('/checkLoginName',		'IndexController:hasSameUser');
$app->any('/suggest',				'IndexController:suggest');
$app->any('/donation',				'IndexController:donation');

$app->any('/book[/{articleid}]',	'IndexController:book');
$app->any('/article/{articleid}',	'IndexController:article');
$app->any('/category[/{key}]',		'IndexController:category');
$app->any('/search',				'IndexController:search');

$app->group('/forums', function() use ($app) {
	$app->any('',						'ForumsController:index');
	$app->any('/thread/{threadid}',		'ForumsController:thread');
	$app->any('/user/{userid}',			'ForumsController:user');
});

$app->group('/user', function() use ($app) {
	$app->any('',							UserController::class.':index');

	$app->group('/edit', function() use ($app) {
		$app->any('/article/[{articleid}]', UserController::class.':editArticle');
		$app->any('/thread/[{threadid}]',	UserController::class.':editThread');
	});

	$app->group('/manage', function() use ($app) {
		$app->get('/',					UserManageController::class.':index');
		$app->any('/userinfo',			UserManageController::class.':userinfo');
		$app->any('/template',			UserManageController::class.':template');
		$app->any('/articles',			UserManageController::class.':articles');
		$app->any('/category',			UserManageController::class.':category');
	});
})->add(function($request, $response, $next) {
	if (empty(Session::get('login'))) {
		return $response->withStatus(302)->withHeader('Location', '/login');
	}
	$response = $next($request, $response);
	return $response;
});



$app->group('/admin', function() use ($app) {
	$app->get('/', 				AdminController::class.':index');
	$app->get('/install',  		AdminController::class.':install');
	$app->get('/users', 		AdminController::class.':users');
	$app->any('/articles', 		AdminController::class.':articles');
	$app->any('/categories', 	AdminController::class.':categories');
	$app->any('/donations', 	AdminController::class.':donations');
	$app->any('/homepage',		AdminController::class.':homepage');

	$app->any('/homecontent',	AdminController::class.':homecontent');

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


$app->group('/forums1', function() use ($app) {
	$app->any('/thread',	ForumsController::class.':thread');
	$app->any('/reply',		ForumsController::class.':reply');

})->add(function($request, $response, $next) {
	if (empty(Session::get('login'))) {
		$launch = $request->getUri()->getPath();
		return $response->withStatus(302)->withHeader('Location', "/login?launch=$launch");
	}
	$response = $next($request, $response);
	return $response;
});

$app->run();
