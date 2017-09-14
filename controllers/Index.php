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
		Session::set(['vc' => $cap->checkcode]);
		return $response;
	}


	function regist($request, $response, $args) {
		$this->container->get('view')->render($response, 'index/regist.html', [

		]);

		return $respose;
		return $response->withStatus(302)->withHeader('Location', '/login');

	}


	function doRegist($request, $response, $args) {
		$this->container->get('view')->render($response, 'index/regist.html', [
		'name' => 'huohongjian'
		]);
		return $response;
	}


	function login($request, $response, $args) {
		if ($request->isPost()) {
			$ds = $request->getParsedBody();

			$user = DB::get('nb_user')->where([
				"login"		=> $ds['login']
			])->selectOne(["userid", "login", "password", "roleid", "name"]);

			if ($user) {
				Session::set($user);
				return $response->withStatus(302)
								->withHeader('Location', '/user/');
			} else {
				return $response->withStatus(302)
								->withHeader('Location', '/login/用户名或密码不正确！');
			}

			

			echo Session::get('name');

		} else {
			$this->container->get('view')->render($response, 'index/login.html', []);
			echo strlen(Session::get('name')).'hhj';
		}
		return $response;
	}



}
