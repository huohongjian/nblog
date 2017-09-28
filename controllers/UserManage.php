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
	$user = DB::get('nb_user')->where(['userid'=>$userid])->selectOne();

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
	if ($request->isPost()) {
		$post = $request->getParsedBody();
		unset($post['login']);
		DB::get('nb_user')->where(['userid'=>$userid])->update($post);
		return $response->getBody()->write('用户信息已修改!');
	} else {
		return $this->container->get('view')->render($response, 'user/manage/userinfo.html',[
			'user' => DB::get('nb_user')->where(['userid'=>$userid])->selectOne()
		]);
	}
}


public function template($request, $response, $args) {

	$this->container->get('view')->render($response, 'user/manage/template.html',[
		
	]);
	return $response;
}


public function articles($request, $response, $args) {
	$userid = $GLOBALS['session']->userid;
	$page = $args['page'] ?? 1;
	$limit = 20;
	$offset = ($page-1) * $limit;
	$sql = "SELECT a.* 
		   FROM nb_article AS a, nb_status AS b
		   WHERE a.userid=$userid and a.status=b.statusid
		   ORDER BY a.artid DESC
		   LIMIT $limit
		   OFFSET $offset";
	$articles = DB::getInstance()->query($sql)->fetchALL();

	$this->container->get('view')->render($response, 'user/manage/articles.html',[
		'articles' => $articles
	]);
	return $response;
}


public function category($request, $response, $args) {

	$this->container->get('view')->render($response, 'user/manage/category.html',[
		
	]);
	return $response;
}


   
}
