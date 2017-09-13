<?php

class User {

	protected $container;

	// constructor receives container instance
	public function __construct(Interop\Container\ContainerInterface $container) {
		$this->container = $container;
	}

	public function index($request, $response, $args) {
		$articles = DB::get('nb_article')
	//				  ->where(['userid' => 2])
					  ->order('artid DESC')
					  ->select();
		$this->container->get('view')->render($response, 'user/articles.html', [
			'articles' => $articles
		]);
		return $response;
	}

	public function contact($request, $response, $args) {
		// your code
		// to access items in the container... $this->container->get('');
		return $response;
	}

	public function edit($request, $response, $args) {
		$this->container->get('view')->render($response, 'kindeditor.html');
		return $response;
	}

	public function save($request, $response, $args) {

		$parsedBody = $request->getParsedBody();

		return $response->withJson($parsedBody);
	}

   
}
