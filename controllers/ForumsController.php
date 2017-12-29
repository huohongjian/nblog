<?php

class ForumsController {

protected $container;

function __construct(Interop\Container\ContainerInterface $container) {
	$this->container = $container;
}


function index($request, $response, $args) {
	parse_str($request->getUri()->getQuery(), $querys);
	$page  = $querys['page'] ?? 1;
	$limit = 10;
	$offset = ($page - 1) * $limit;
	unset($querys['page']);

	$this->container->get('view')->render($response, 'forums/index.html', [
		//'homeNav'	=> Article::getOne('system-home-nav')['content'],
		'categories'=> Category::getAll('name'), 
		'threads'	=> Thread::get($querys, 'a.thid DESC', $offset, $limit),
		'hotThreads'=> Thread::get($querys, 'a.replynum DESC', $offset, $limit),
		'forReply'	=> Thread::get($querys, 'a.replynum', $offset, $limit), 
		'nums'		=> Thread::num($querys),
		'usernum'	=> User::num(),
	]);
	return $response;
}


function thread($request, $response, $args) {
	
	if ($request->isGet()) {
		parse_str($request->getUri()->getQuery(), $querys);
		$page  = $querys['page'] ?? 1;
		$limit = 3;
		$offset = ($page - 1) * $limit;
		
		return $this->container->get('view')->render($response, 'forums/thread.html', [
			'categories'=> Category::getAll('name'), 
			'thread'	=> Thread::getOne($args['threadid']),
			'replies'	=> Reply::getByThreadid($args['threadid'], $offset, $limit),
			'pages'		=> ['curPage'=>$page, 'perItem'=>$limit],
		]);
		return $response;

	} else if ($request->isPost()) {
		$roleid = Session::get('roleid') ?? 6;
		$userid = Session::get('userid');
		$post = $request->getParsedBody();

		if (empty($userid)) {
//			return $response->withStatus(302)->withHeader('Location','login?launch=/forums/thread/'. $post['threadid']);
			return $response->withJson(array('msg'=>'请先登录'));
		}


		if ((int)$post['replyid'] < 0) {
			$post['userid'] = $userid;
			Reply::insert($post);
			return $response->withJson(array('msg'=>'回贴成功!'));
		} else {
			Reply::update($post);
			return $response->withJson(array('msg'=>'更新成功！'));
		}

	} else if ($request->isPut()) {
		$post = $resquest->getParsedBody();

		return $response->withJson($post);
	}


}


function user($request, $response, $args) {
		return $this->container->get('view')->render($response, 'forums/index.html', [
			'categories'=> Category::getAll('name'), 
			'threads'	=> Thread::getByUserid((int)$args['userid']),
		]);
}




}
