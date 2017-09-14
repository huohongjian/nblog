<?php

class Article {

	protected $container;

	public function __construct(Interop\Container\ContainerInterface $container) {
		$this->container = $container;
		
	}

	private function getArticleById($id) {
		return DB::get("nb_article")->where(array('articleid'=>$id))->selectOne();
	}


	// 显示文章内容
	function index($request, $response, $args) {
		$id = $args['articleid'];
		$article = DB::get("nb_article")->where(array('articleid'=>$id))->selectOne();
		$this->container->get('view')->render($response, 'article/index.html',
			array('article'=>$article)
		);
		return $response;
	}


	public function list($request, $response, $args) {

		$rs = DB::get('nb_article')->select();
		
		$this->container->get('view')->render($response, 'article/list.html',
			array('articles'=>$rs)
		);
		return $response;
	}


	public function edit($request, $response, $args) {
		$id = $args['articleid'];
		$article = empty($id) ? array() : $this->getArticleById($id);

		$this->container->get('view')->render($response, 'article/kindeditor.html',
			array('article' => $article)
		);
		return $response;
	}


	public function save($request, $response, $args) {

		$p = $request->getParsedBody();
		if (empty($p['articleid'])) {
			$p['articleid'] = uniqid();
		}
		$id = DB::get('nb_article')->returning('articleid')->conflict('articleid')->upsert($p);

		return $response->withJson(['status'=>200, 'articleid'=>$id]);
	}

   
}
