<?php

class UserController {

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
			'pages'		=> ['perItem'=>$limit],
			'homeNav'	=> Article::getOne('system-home-nav')['content'],
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
			'articles' => DB::ins()->query($SQL)->data(),
			'pages' => ['totItem'=>DB::ins()->query($sql)->val()]
		]);
	}


}



function editArticle($request, $response, $args) {
	$querys = $request->getUri()->getQuery();
	$roleid = Session::get('roleid') ?? 6;
	$userid = Session::get('userid');

	if ($request->isGet()) {
		$id = $args['articleid'];
		$editor = $request->getUri()->getQuery();
		if ($editor!='wangeditor' && $editor!='kindeditor') {
			$editor = 'trumbowyg';
		}

		if (strlen($id)<13) {
			$article = array();
		} else {
			$article = DB::ins()->select('nb_article', ['articleid'=>$id])->one();
			if ($article['userid'] != $userid and $roleid>2) {
				return	$response->withStatus(303)->withHeader('Location', '/user/edit/new');
			}
			$userid = $article['userid'];
		}
		$cats = DB::ins()->select('nb_user', ['userid'=>$userid],
				'', 'categories')->val();
		return $this->container->get('view')->render($response, 'user/'.$editor.'.html', [
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
			$post['newtime'] = date("Y-m-d H:i:s");
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


function editThread($request, $response, $args) {
	$roleid = Session::get('roleid') ?? 6;
	$userid = Session::get('userid');

	if ($request->isGet()) {
		$this->container->get('view')->render($response, 'user/threadeditor.html', [
			'categories'=> Category::getAll('name'), 
	
		]);

	} else if ($request->isPost()) {
		$status 	= 400;
		$threadid	= 'new';
		$msg		= '您尚未登录!';

		if ($userid>0) {
			$post = $request->getParsedBody();
			if (strlen($post['threadid']<13)) {
				$post['userid'] = $userid;
				$threadid		= Thread::insert($post);
				$msg 			= '帖子发布成功!';
				$status			= 200;
			} else {
				if ($userid==$post['userid'] || $roleid<3) {
					$threadid 	= Thread::update($post);
					$msg 		= '帖子更新成功!';
					$status		= 200;
				} else {
					$msg = '您无权更新帖子!';
				}
			}
		}
		return $response->withJson([
			'status'	=> $status,
			'threadid'	=> $threadid,
			'msg'		=> $msg
		]);
	}
	return $response;

}

   
}
