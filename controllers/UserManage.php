<?php

class UserManage {

protected $container;

public function __construct(Interop\Container\ContainerInterface $container) {
	$this->container = $container;
	
}


function index($request, $response, $args) {

	$this->container->get('view')->render($response, 'user/manage/index.html',[
		
	]);
	return $response;
}


public function renewpassword($request, $response, $args) {
	$post = $request->getParsedBody();
	$userid = $GLOBALS['session']->userid;
	$user = DB::get('nb_user')->where(['userid'=>$userid])->select()->one();

	$key = base64_encode(pack("H*", $post['pwd0']));
	$pwd0 = Rsa::privDecrypt($key, true);

	if ($pwd0 == $user['password']) {
		$key = base64_encode(pack("H*", $post['pwd1']));
		$pwd1 = Rsa::privDecrypt($key, true);
		DB::get('nb_user')->where(['userid'=>$userid])->update(['password'=>$pwd1]);
		return $response->getBody()->write('密码已修改!');
	} else {
		return $response->getBody()->write('原密码不正确!');
	}
}


public function userinfo($request, $response, $args) {
	$userid = $GLOBALS['session']->userid;
	if ($request->isGet()) {
		return $this->container->get('view')->render($response, 'user/manage/userinfo.html',[
			'user' => DB::get('nb_user')->where(['userid'=>$userid])->select()->one()
		]);

	} else {
		$input = $request->getParsedBody();
		unset($input['login']);
		DB::get('nb_user')->where(['userid'=>$userid])->update($input);
		return $response->getBody()->write('用户信息已修改!');
	}
}


function template($request, $response, $args) {

	$this->container->get('view')->render($response, 'user/manage/template.html',[
		
	]);
	return $response;
}



function articles($request, $response, $args) {
	$userid = (int)$GLOBALS['session']->userid;

	if ($request->isGet()) {
		$c = DB::get('nb_user')->where(['userid'=>$userid])->select('categories')->val();
		return $this->container->get('view')->render($response, 'user/manage/articles.html',[
			'categories' => explode(',', $c)
		]);

	} else {
		$input  = $request->getParsedBody();
		$page   = $input['page'] ? (int)$input['page'] : 1;
		$limit  = 10;
		$offset = ($page-1) * $limit;
		
		if ($request->isPost()) {
			
			$where = DB::struck([
				'userid' => $userid,
				'status' => $input['status'],
				'category' => $input['category']
			], 'AND')->kvs;

			$key = $input['key'];
			if (!empty($key)) {
				$where .= ' AND '.DB::clear($input['range'])." LIKE ".DB::escape("%{$key}%");
			}

			$sql = "SELECT count(*) FROM nb_article WHERE $where";
			$SQL = str_replace('count(*)', 'articleid,title,status,category,counter,addtime',
								$sql)." ORDER BY artid DESC LIMIT $limit OFFSET $offset";

			return $response->withJson([
				'articles' => DB::ins()->query($SQL)->arr(),
				'pages' => ['perItem'=>$limit, 'totItem'=>DB::ins()->query($sql)->val()]
			]);

		} else if ($request->isPut()) {
			$ids = $input['ids'];
			$sta = $input['status'];
			$cat = $input['category'];

			if (empty($ids)) {
				$manage = '请选择文档!';
			} else if (empty($sta) and empty($cat)) {
				$manage = '请选择操作!';
			} else {
				$set = DB::struck(['status'=>$sta, 'category'=>$cat])->kvs;
				$ids = DB::struck($ids)->vs;
				$sql = "UPDATE nb_article SET $set WHERE articleid in ($ids)";
				if (DB::ins()->query($sql)) {
					$manage = '批量操作成功!';
				} else {
					$manage = '批量操作失败!';
				}
			}
			$response->getBody()->write($manage);
		}
	}
	return $response;
}



function category($request, $response, $args) {
	$userid = (int)$GLOBALS['session']->userid;

	if ($request->isGet()) {
		$c = DB::get('nb_user')->where(['userid'=>$userid])->select('categories')->val();
		return $this->container->get('view')->render($response, 'user/manage/category.html',[
			'categories' => $c
		]);
	} else {
		

	}
	return $response;
}


   
}
