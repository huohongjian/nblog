<?php

class User {

	protected $container;

	// constructor receives container instance
	public function __construct(Interop\Container\ContainerInterface $container) {
		$this->container = $container;
	}

	public function index($request, $response, $args) {
		$userid =123;// $GLOBALS['session'];

	//	print_r($userid);
		echo $userid;
		return $response;

		$cat = DB::get('nb_user')->where(['userid'=>$userid])->selectVal('categories');
		$articles = DB::get('nb_article')
	//				  ->where(['userid' => 2])
					  ->order('artid DESC')
					  ->selectAll();
		$this->container->get('view')->render($response, 'user/index.html', [
			'categories'=> explode(',', $cat),
			'articles' 	=> $articles
		]);
		return $response;
	}

	public function manage($request, $response, $args) {
		return $this->container->get('view')->render($response, 'user/manage.html', [
			
		]);
		return $response;
	}

	public function editArticle($request, $response, $args) {
		$userid = Session::get('userid');
		$id = $args['articleid'];
		$article = empty($id) ? array() : DB::get('nb_article')->where(['articleid'=>$id])->selectOne();
		$cats = DB::get('nb_user')->where(['userid'=>$userid])->selectVal('categories');
		$this->container->get('view')->render($response, 'user/kindeditor.html', [
			'categories' => explode(',', $cats),
			'article' => $article,
			'maxnum'  => 12,
		]);
		return $response;
	}

	public function saveArticle($request, $response, $args) {

		$post = $request->getParsedBody();

		if (strlen($post['articleid'])<13) {
			$post['articleid'] = uniqid();
		}
		$id = DB::get('nb_article')->returning('articleid')->conflict('articleid')->upsert($post);
		return $response->withJson(['status'=>200, 'articleid'=>$id, 'msg'=>'保存文章成功!']);
		return $response; //必须返回上一行
	}

   
}
