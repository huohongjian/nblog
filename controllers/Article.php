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

		$article = DB::get("nb_article")->where(['articleid'=>$args['articleid']])->selectOne();
		$this->container->get('view')->render($response, 'article/index.html',
			array('article'=>$article)
		);
		return $response;
	}


	public function list($request, $response, $args) {

		$rs = DB::get('nb_article')->selectAll();
		
		$this->container->get('view')->render($response, 'article/list.html',
			array('articles'=>$rs)
		);
		return $response;
	}


   
}
