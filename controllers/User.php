<?php

class User {

protected $container;

// constructor receives container instance
public function __construct(Interop\Container\ContainerInterface $container) {
	$this->container = $container;
}

// 用户文章列表
function index($request, $response, $args) {
	$userid = (int)Session::get('userid');
	$limit = 48;

	if ($request->isGet()) {
		$user = DB::ins()->select('nb_user', ['userid'=>$userid], '', 'categories,photo')->one();
		return $this->container->get('view')->render($response, 'user/index.html', [
			'userphoto' => $user['photo'],
			'categories'=> explode(',', $user['categories']),
			'pages'		=> ['perItem'=>$limit]
		]);

	} else if ($request->isPost()) {
		$post = $request->getParsedBody();
		$offset = ((int)$post['page'] - 1) * $limit;

		$where = DB::where([
			'userid'	=> $userid,
			'category' 	=> $post['category'],
		]) . " AND status<>'删除'";
		if (!empty($post['key'])) {
			$where .= ' AND '. DB::like($post['range'], $post['key']);
		}
		$sql = "SELECT count(*) FROM nb_article {$where}";
		$SQL = "SELECT articleid, title, alias FROM nb_article {$where}
				ORDER BY artid DESC LIMIT {$limit} OFFSET {$offset}";

		return $response->withJson([
			'articles' => DB::ins()->query($SQL)->rows(),
			'pages' => ['totItem'=>DB::ins()->query($sql)->val()]
		]);
	}


}



function edit($request, $response, $args) {
	$roleid = Session::get('roleid') ?? 6;
	$userid = Session::get('userid');

	if ($request->isGet()) {
		$id = $args['articleid'];

		if (strlen($id)<13) {
			$article = array();
		} else {
			$article = DB::ins()->select('nb_article', ['articleid'=>$id])->one();
			if ($article['userid'] != $userid and $roleid>2) {
				return	$response->withStatus(303)->withHeader('Location', '/user/edit/new');
			}
		}

		$cats = DB::ins()->select('nb_user', ['userid'=>$userid], '', 'categories')->val();
		return $this->container->get('view')->render($response, 'user/bsdeditor.html', [
			'categories' => explode(',', $cats),
			'article' => $article,
			'maxnum'  => 13,
		]);

	} else if ($request->isPost()) {
		$post = $request->getParsedBody();
		
		if (strlen($post['articleid'])<13) {
			$sql = "UPDATE nb_user SET score=score+1 WHERE userid=$1";
			DB::ins()->query2($sql, [$userid]);

			$post['userid'] = $userid;
			$post['articleid'] = uniqid();
			$articleid = DB::ins()->insert('nb_article', $post, 'RETURNING articleid')->val();
			return $response->withJson([
				'status'	=> 200,
				'msg'		=> '文章添加成功!',
				'articleid' => $articleid
			]);
		} else {
			$post['newtime'] = date("Y-m-d h:i:s");
			$r = DB::ins()->update('nb_article', $post, ['articleid'=>$post['articleid']])
					->affectedRows();
			if ($r>0) {
				return $response->withJson([
					'status'	=> 200,
					'msg'		=> '文章保存成功!',
					'articleid' => $post['articleid']
				]);
			}
				
		}
	}




}


   
}
