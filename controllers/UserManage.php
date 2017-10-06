<?php

class UserManage {

protected $container;

public function __construct(Interop\Container\ContainerInterface $container) {
	$this->container = $container;
	
}


function index($request, $response, $args) {

	$this->container->get('view')->render($response, 'user/manage/index.html',[
		'articleNum'=>DB::ins()->select('nb_user', ['userid'=>$userid], '', 'score')->val()
	]);
	return $response;
}


public function renewpassword($request, $response, $args) {
	$post = $request->getParsedBody();
	$userid = Session::all('userid');

	$user = DB::ins()->select('nb_user', ['userid'=>$userid])->one();

	$key = base64_encode(pack("H*", $post['pwd0']));
	$pwd0 = Rsa::privDecrypt($key, true);

	if ($pwd0 == $user['password']) {
		$key = base64_encode(pack("H*", $post['pwd1']));
		$pwd1 = Rsa::privDecrypt($key, true);
		DB::ins()->update('nb_user', ['password'=>$pwd1], ['userid'=>$userid]);
		return $response->getBody()->write('密码已修改!');
	} else {
		return $response->getBody()->write('原密码不正确!');
	}
}


public function userinfo($request, $response, $args) {
	$userid = Session::all('userid');

	if ($request->isGet()) {
		return $this->container->get('view')->render($response, 'user/manage/userinfo.html',[
			'user' => DB::ins()->select('nb_user', ['userid'=>$userid])->one()
		]);

	} else {
		$input = $request->getParsedBody();
		unset($input['login']);
		DB::ins()->update('nb_user', $input, ['userid'=>$userid]);
		return $response->getBody()->write('用户信息已修改!');
	}
}


function template($request, $response, $args) {

	$this->container->get('view')->render($response, 'user/manage/template.html',[
		
	]);
	return $response;
}



function articles($request, $response, $args) {
	$userid = Session::all('userid');

	if ($request->isGet()) {
		$c = DB::ins()->select('nb_user', ['userid'=>$userid], '', 'categories')->val();
		return $this->container->get('view')->render($response, 'user/manage/articles.html',[
			'categories' => explode(',', $c)
		]);

	} else {
		$input  = $request->getParsedBody();
		$page   = $input['page'] ? (int)$input['page'] : 1;
		$limit  = 10;
		$offset = ($page-1) * $limit;
		
		if ($request->isPost()) {
			
			$where = DB::where([
				'userid' => $userid,
				'status' => $input['status'],
				'category' => $input['category']
			]);

			if (!empty($input['key'])) {
				$where .= ' AND '.DB::like($input['range'], $input['key']);
			}

			$sql = "SELECT count(*) FROM nb_article $where";
			$SQL = "SELECT articleid,title,status,category,counter,addtime
					FROM nb_article
					$where
					ORDER BY artid DESC LIMIT $limit OFFSET $offset";

			return $response->withJson([
				'articles' => DB::ins()->query($SQL)->rows(),
				'pages' => ['perItem'=>$limit, 'totItem'=>DB::ins()->query($sql)->val()]
			]);

		} else if ($request->isPut()) {
			$ids = $input['ids'];
			
			if (empty($ids)) {
				$manage = '请选择文档!';

			} else {
				unset($input['ids']);
				$set = DB::struck($input, $exEmpty=true)->upsets;
				if (empty($set)) {
					$manage = '请选择操作!';
				} else {
					$sql = 'UPDATE nb_article SET ' . $set
						 . ' WHERE articleid in (' . DB::struck($ids)->values . ')';
					$manage = DB::ins()->query($sql) ? '批量操作成功!' : '批量操作失败!';
				}
			}
			$response->getBody()->write($manage);
		}
	}
	return $response;
}



function category($request, $response, $args) {
	$userid = Session::all('userid');

	if ($request->isGet()) {
		$c = DB::ins()->select('nb_user', ['userid'=>$userid], '', 'categories')->val();
		$d = DB::ins()->select('nb_category',[],'ORDER BY categoryid','name')->vals();
		return $this->container->get('view')->render($response, 'user/manage/category.html',[
			'categories' => $c,
			'defaultCategories'	 => implode(',', $d)
		]);

	} else if ($request->isPost()) {
		$input = $request->getParsedBody();
		DB::ins()->update('nb_user', $input, ['userid'=>$userid]);
		return $response->getBody()->write('文章类别已更改!');
	}
}


   
}
