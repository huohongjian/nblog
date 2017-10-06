<?php

class User {

	protected $container;

	// constructor receives container instance
	public function __construct(Interop\Container\ContainerInterface $container) {
		$this->container = $container;
	}

	// 显示用户文章列表
	public function index($request, $response, $args) {
		$userid = Session::all('userid');

		$user = DB::ins()->select('nb_user', ['userid'=>$userid], '', 'categories,photo')->one();
		$arts = DB::ins()->select('nb_article', ['userid'=>$userid, 'category'=>$args['category']])->all();

		return $this->container->get('view')->render($response, 'user/index.html', [
			'userphoto' => $user['photo'],
			'categories'=> explode(',', $user['categories']),
			'articles' 	=> $arts
		]);
	}

	public function manage($request, $response, $args) {
		return $this->container->get('view')->render($response, 'user/layout.html', [
			
		]);
		return $response;
	}

	public function editArticle($request, $response, $args) {
		$roleid = Session::all('roleid') ?? 6;
		$userid = Session::all('userid');
		$id = $args['articleid'];

		if (strlen($id)<13) {
			$article = array();
		} else {
			$article = DB::ins()->select('nb_article', ['articleid'=>$id])->one();
			if ($article['userid'] != $userid and $roleid>2) {
				
				return	$response->withStatus(303)->withHeader('Location', '/user/article/edit/new');
			}
		}

		$cats = DB::ins()->select('nb_user', ['userid'=>$userid], '', 'categories')->val();

		$this->container->get('view')->render($response, 'user/bsdeditor.html', [
			'categories' => explode(',', $cats),
			'article' => $article,
			'maxnum'  => 12,
		]);
		return $response;
	}


	public function saveArticle($request, $response, $args) {
		$post = $request->getParsedBody();
		
		if (strlen($post['articleid'])<13) {
			$post['userid'] = Session::all('userid');
			$post['articleid'] = uniqid();
			$sql = "UPDATE nb_user SET score=score+1 WHERE userid=$1";
			DB::ins()->query2($sql, [$post['userid']]);

			$articleid = DB::ins()->insert('nb_article', $post, 'RETURNING articleid')->val();
			return $response->withJson([
				'status'	=> 200,
				'msg'		=> '文章添加成功!',
				'articleid' => $articleid
			]);
		} else {
			$post['newtime'] = date("Y-m-d h:i:s");
			if ( DB::ins()->update('nb_article', $post, ['articleid'=>$post['articleid']])
						  ->affectedRows() ) {
				return $response->withJson([
					'status'	=> 200,
					'msg'		=> '文章保存成功!',
					'articleid' => $post['articleid']
				]);
			}
				
		}
		return $response->withJson([
				'status'	=> 400,
				'msg'		=> '文章添加/保存失败!'
			]);
		return $response; //必须返回上一行
	}



   
}
