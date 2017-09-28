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


	// public function bsdeditor($request, $response, $args) {
	// 	$userid = Session::get('userid');
	// 	$id = $args['articleid'];
	// 	$article = strlen($id)<13 ? array() : DB::get('nb_article')->where(['articleid'=>$id])->selectOne();
	// 	$cats = DB::get('nb_user')->where(['userid'=>$userid])->selectVal('categories');
	// 	$this->container->get('view')->render($response, 'user/bsdeditor.html', [
	// 		'categories' => explode(',', $cats),
	// 		'article' => $article,
	// 		'maxnum'  => 12,
	// 	]);
	// 	return $response;
	// }

	public function editArticle($request, $response, $args) {
		$userid = $GLOBALS['session']->userid;
		$id = $args['articleid'];

		if (strlen($id)<13) {
			$article = array();
		} else {
			$article = DB::get('nb_article')->where(['articleid'=>$id])->selectOne();
			if ($article['userid'] != $userid and $GLOBALS['session']->roleid>2) {
				$article = array();
			}
		}

		$cats = DB::get('nb_user')->where(['userid'=>$userid])->selectVal('categories');
		$this->container->get('view')->render($response, 'user/bsdeditor.html', [
			'categories' => explode(',', $cats),
			'article' => $article,
			'maxnum'  => 12,
		]);
		return $response;
	}


	public function saveArticle($request, $response, $args) {
		$post = $request->getParsedBody();
		$post['userid'] = $GLOBALS['session']->userid;
		
		if (strlen($post['articleid'])<13) {
			$post['articleid'] = uniqid();
			$sql = "UPDATE nb_user SET score=score+1 WHERE userid=$1";
			DB::getInstance()->query2($sql, [$post['userid']]);

			$r = DB::get('nb_article')->returning('articleid')->insert($post);
			return $response->withJson([
				'status'	=> 200,
				'msg'		=> '文章添加成功!',
				'articleid' => $r['articleid']
			]);
		} else {
			$post['newtime'] = date("Y-m-d h:i:s");
			DB::get('nb_article')->where(['articleid'=>$post['articleid']])->update($post);
			return $response->withJson([
				'status'	=> 200,
				'msg'		=> '文章保存成功!',
				'articleid' => $post['articleid']
			]);
		}
		return $response->withJson([
				'status'	=> 400,
				'msg'		=> '文章添加/保存失败!'
			]);
		return $response; //必须返回上一行
	}



   
}
