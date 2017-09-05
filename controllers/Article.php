<?php

class Article {

	protected $container;

	public function __construct(Interop\Container\ContainerInterface $container) {
		$this->container = $container;
		
	}

	public function list($request, $response, $args) {

		$articleModel = new ArticleModel;
		$rs = $articleModel->list();
		
		$this->container->get('view')->render($response, 'article_list.html',
			array('articles'=>$rs)
		);


		return $response;
	}


	public function edit($request, $response, $args) {
		$this->container->get('view')->render($response, 'kindeditor.html');
		return $response;
	}

	public function save($request, $response, $args) {

		$p = $request->getParsedBody();
		$article = new ArticleModel();
		foreach ($p as $key => $value) {
			$article->$key = $value;
		}


		$id = $article->add();

		return $response->withJson(['articleid' => $id]);
	}

   
}
