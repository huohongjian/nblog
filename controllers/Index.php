<?php

class Index {

protected $container;

function __construct(Interop\Container\ContainerInterface $container) {
	$this->container = $container;
}

function index($request, $response, $args) {

	$this->container->get('view')->render($response, 'index/index.html', [
		'articles' => DB::ins()->select('nb_article', [], 'ORDER BY artid DESC LIMIT 6',
								 'articleid, title')->all()
	]);
	return $response;
}


// function captcha($request, $response, $args) {
// 	$cap = new Captcha();
// 	$cap->create();
// 	Session::set(['ca' => $cap->checkcode]);
// 	return $response;
// }


// function checkLoginName($request, $response, $args) {
// 	$post = $request->getParsedBody();
// 	if (DB::get('nb_user')->where(['login'=>$post['login']])->select('count(*)')->val()) {
// 		echo '存在同名用户!';
// 	}
// 	return $response;
// }

// function regist($request, $response, $args) {
// 	if ($request->isPost()) {
// 		$post = $request->getParsedBody();
// 		if (empty($post['name'])) {
// 			$message = '用户名不得为空!';
// 		} elseif (strlen($post['login'])<3) {
// 			$message = '登录名长度不得小于3位!';
// 		} elseif (strtoupper($post['captcha']) != strtoupper(Session::get('ca'))) {
// 			$message = '验证码错误!';
// 		} elseif (DB::get('nb_user')->where(['login'=>$post['login']])->select('count(*)')->val()) {
// 			$message = '存在同名用户!';
// 		} else {
// 			unset($post['captcha']);
// 			$key = base64_encode(pack("H*", $post['password']));
// 			$post['password'] = Rsa::privDecrypt($key, true);
// 			if ( DB::get('nb_user')->insert($post)->affectedRows() ) {
// 				return $response->withStatus(302)->withHeader('Location', '/login');
// 			}
// 		}
// 	}
// 	return $this->container->get('view')->render($response, 'index/regist.html', [
// 		'errorMessage' => $message
// 	]);
// }


// function login($request, $response, $args) {
// 	if ($request->isPost()) {
// 		$post = $request->getParsedBody();

// 		if (strtoupper($post['captcha']) == strtoupper(Session::get('ca'))) {
// 			$user = DB::get('nb_user')->where(["login" => $post['login']])
// 					->select('userid, login, password, roleid, name, photo')->one();
// 			if ($user) {
// 				$key = base64_encode(pack("H*", $post['password']));
// 				$post['password'] = Rsa::privDecrypt($key, true);
// 				if ($post['password'] == md5($post['captcha'].$user['password'].$post['captcha'])) {
// 					unset($user['password']);
// 					Session::set($user);
// 					return $response->withStatus(302)->withHeader('Location', '/user');
// 				} else {
// 					$message = '登录密码不正确!';
// 				}
// 			} else {
// 				$message = '登录名不存在!';
// 			}
// 		} else {
// 			$message = '验证码错误!';
// 		}
// 	}
// 	return $this->container->get('view')->render($response, 'index/login.html', [
// 		'errorMessage' => $message
// 	]);
// }


// function logout($request, $response, $args) {
// 	Session::clear();
// 	return $response->withStatus(302)->withHeader('Location', '/');
// }


// function suggest($request, $response, $args) {
// 	if ($request->isPost()) {
// 		$post = $request->getParsedBody();
// 		DB::get('nb_suggest')->insert($post);
// 		return $response->getBody()->write('感谢您的宝贵建议!');
// 	} else {

// 		return $this->container->get('view')->render($response, 'index/suggest.html', [
// 			'suggests' => DB::get('nb_suggest')->order('suggestid DESC')->select()->all()
// 		]);
// 	}
	
// }


}
