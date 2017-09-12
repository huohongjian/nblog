<?php

class Index {

	protected $container;

	function __construct(Interop\Container\ContainerInterface $container) {
		$this->container = $container;
	}

	function index($request, $response, $args) {
		// your code
		// to access items in the container... $this->container->get('');
		$this->container->get('view')->render($response, 'index.html', [
		'name' => 'huohongjian'
		]);
		return $response;
	}


	function regist($request, $response, $args) {
		$this->container->get('view')->render($response, 'regist.html', [

		]);

		$this->container->get('flash')->addMessage('Test', 'This is a message');

		return $response->withStatus(302)->withHeader('Location', '/login');

	}


	function doRegist($request, $response, $args) {
		$this->container->get('view')->render($response, 'regist.html', [
		'name' => 'huohongjian'
		]);
		return $response;
	}


	function login($request, $response, $args) {
		if ($request->isPost()) {
			$postDatas = $request->getParsedBody();
			print_r($postDatas);



		} else {
			$this->container->get('view')->render($response, 'login.html', []);
			$message = $this->container->get('flash');
			print_r($message);
		}
		return $response;
	}



}
