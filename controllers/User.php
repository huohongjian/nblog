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
					  ->selectAll();
		$this->container->get('view')->render($response, TEMPLATE.'/user/index.html', [
			'articles' => $articles
		]);
		return $response;
	}

	public function contact($request, $response, $args) {
		// your code
		// to access items in the container... $this->container->get('');
		return $response;
	}

	public function editArticle($request, $response, $args) {
		$userid = Session::get('userid');
		$id = $args['articleid'];
		$article = empty($id) ? array() : DB::get('nb_article')->where(['articleid'=>$id])->selectOne();
		$cats = DB::get('nb_user')->where(['userid'=>$userid])->selectVal(['categories']);
		return $this->container->get('view')->render($response, TEMPLATE.'/article/kindeditor.html', [
			'categories' => explode(',', $cats),
			'article' => $article,
			'maxnum'  => 12,
		]);
	}

	public function saveArticle($request, $response, $args) {

		$p = $request->getParsedBody();
		if (empty($p['articleid'])) {
			$p['articleid'] = uniqid();
		}
		$id = DB::get('nb_article')->returning('articleid')->conflict('articleid')->upsert($p);

		return $response->withJson(['status'=>200, 'articleid'=>$id, 'msg'=>'保存文章成功!']);
	}

   
}
