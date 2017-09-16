<?php

class Index {

protected $container;

function __construct(Interop\Container\ContainerInterface $container) {
	$this->container = $container;
}

function index($request, $response, $args) {
	$this->container->get('view')->render($response, 'index/index.html', [
		'name' => 'huohongjian'
	]);
	return $response;
}


function captcha($request, $response, $args) {
	$cap = new Captcha();
	$cap->create();
	Session::set(['ca' => $cap->checkcode]);
	return $response;
}


function hasSameUser($request, $response, $args) {
	echo DB::get('nb_user')->where(['login'=>$args['login']])->selectVal(['count(*)']);
	return $response;
}

function regist($request, $response, $args) {
	if ($request->isPost()) {
		$ds = $request->getParsedBody();
		if (empty($ds['name'])) {
			$message = '用户名不得为空!';
		} elseif (strlen($ds['login'])<3) {
			$message = '登录名长度小于3位!';
		} elseif (strtoupper($ds['captcha']) != strtoupper(Session::get('ca'))) {
			$message = '验证码错误!';
		} elseif (DB::get('nb_user')->where(['login'=>$ds['login']])->selectVal(['count(*)'])) {
			$message = '存在同名用户!';
		} else {
			$key = base64_encode(pack("H*", $ds['password']));
			$ds['password'] = Rsa::privDecrypt($key, true);
			unset($ds['captcha'], $ds['password2']);
			DB::get('nb_user')->insert($ds);
			return $response->withStatus(302)->withHeader('Location', '/login/');
		}
	}
	return $this->container->get('view')->render($response, 'index/regist.html', [
		'errorMessage' => $message
	]);
}


function login($request, $response, $args) {
	if ($request->isPost()) {
		$ds = $request->getParsedBody();

		if ($ds['captcha'] == Session::get('ca')) {

			$user = DB::get('nb_user')->where(["login" => $ds['login']])
					->selectOne(["userid", "login", "password", "roleid", "name"]);

			if ($user) {
				$key = base64_encode(pack("H*", $ds['password']));
				$ds['password'] = Rsa::privDecrypt($key, true);
				if ($ds['password'] == md5($user['password'] . $ds['captcha']))
					Session::set([
						'userid' => $user['userid'],
					]);
					return $response->withStatus(302)->withHeader('Location', '/user/');
				} else {
					$message = '登录密码不正确!';
				}
			} else {
				$message = '登录名不存在!';
			}
		} else {
			$message = '验证码错误!';
		}

		
	}
	return $this->container->get('view')->render($response, 'index/login.html', [
		'errorMessage' => $message
	]);
}



}
