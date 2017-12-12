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
		'categories'=> Category::getAll('name'), 
		'threads'	=> Thread::get($querys, $offset, $limit),
		'pages'		=> array(curPage=>$page, totItem=>Thread::num($querys), perItem=>$limit),
	]);
	return $response;
}


function thread($request, $response, $args) {
	
	if ($request->isGet()) {
		
		return $this->container->get('view')->render($response, 'forums/thread.html', [
			'categories'=> Category::getAll('name'), 
			'thread'	=> Thread::getOne($args['threadid']),
			'replies'	=> Reply::getByThreadid($args['threadid']),
		]);
		return $response;
	} else if ($request->isPost()) {
		$roleid = Session::get('roleid') ?? 6;
		$userid = Session::get('userid');
		if (empty($userid)) {
			return $response->withJson(array('msg'=>'请先登录'));
		}

		$post = $request->getParsedBody();

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
