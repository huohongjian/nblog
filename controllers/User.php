<?php

class User {

	protected $container;

	// constructor receives container instance
	public function __construct(Interop\Container\ContainerInterface $container) {
		$this->container = $container;
	}

	// 显示用户文章列表
	public function index($request, $response, $args) {
		$userid = $GLOBALS['session']->userid;

		$category = $args['category'];
		$sql = "SELECT * FROM nb_article WHERE userid='$userid'";
		if ($category) {
			$sql .= " AND category='".pg_escape_string($category)."'";
		}
		$sql .= " ORDER BY artid DESC";

		$user = DB::get('nb_user')->where(['userid'=>$userid])->selectOne('categories,photo');

		$articles = DB::getInstance()->query($sql)->fetchAll();
		return $this->container->get('view')->render($response, 'user/index.html', [
			'userphoto' => $user['photo'],
			'categories'=> explode(',', $user['categories']),
			'articles' 	=> $articles
		]);
	}

	public function manage($request, $response, $args) {
		return $this->container->get('view')->render($response, 'user/layout.html', [
			
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
		if (strlen($post['articleid'])<9) {
			$post['articleid'] = uniqid();
		}
		
		$post['userid'] = $GLOBALS['session']->userid;


		$id = DB::get('nb_article')->returning('articleid')->conflict('articleid')->upsert($post);
		if ($id) {
			return $response->withJson(['status'=>200, 'msg'=>'保存成功!', 'articleid'=>$id]);
		} else {
			return $response->withJson(['status'=>400, 'msg'=>'保存失败!']);
		}
		return $response; //必须返回上一行
	}




   
}
