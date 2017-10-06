<?php

class Article {

protected $container;

function __construct(Interop\Container\ContainerInterface $container) {
	$this->container = $container;
	
}

// 显示文章内容
function index($request, $response, $args) {
	$id = pg_escape_string($args['articleid']);
	$sql = "UPDATE nb_article SET counter=counter+1 WHERE articleid='$id';
			SELECT a.*, b.name AS username FROM nb_article AS a
			LEFT JOIN nb_user AS b ON a.userid=b.userid
			WHERE a.articleid='$id'";
	$article = DB::ins()->query($sql)->one();

	return $this->container->get('view')->render($response, 'article/index.html', [
		'article'=>$article,
		'user' => [ 'userid'=>Session::all('userid'),
					'roleid'=>Session::all('roleid')
				  ]
	]);
}


function search($request, $response, $args) {
	if ($args['key']) {
		$sql = "SELECT * FROM nb_article WHERE title LIKE '%"
			 . pg_escape_string($args['key'])
			 . "%' ORDER BY artid DESC";
		$rs = DB::ins()->query($sql)->all();
	} else {
		$sql = "SELECT * FROM nb_article ORDER BY artid DESC";
		$rs = DB::ins()->query($sql)->all();
	}
	return $this->container->get('view')->render($response, 'article/search.html', [
		'articles' => $rs
	]);
}


function category($request, $response, $args) {
	$limit = 20;

	if ($request->isGet()) {
		$n = DB::ins()->select('nb_article', ['category'=>$args['key']], '', 'count(*)')->val();
		return $this->container->get('view')->render($response, 'article/category.html', [
			'categoryName' => $args['key'] ?? '全部',
			'pages' 	=> ['perItem'=>$limit, 'totItem'=>$n],
			'categories'=> DB::ins()->select('nb_category',[],'ORDER BY categoryid','name')->all()
		]);

	} else if ($request->isPost()) {
		$page  = $request->getParsedBody()['page'];
		$offset= ((int)$page - 1) * $limit;
		
		$rs = DB::ins()->select('nb_article', ['category'=>$args['key'], 'status'=>'公开'],
				'ORDER BY articleid DESC LIMIT '.$limit.' OFFSET '.$offset, 'articleid, title, alias')->rows();
		return $response->withJson([
			'articles' 	=> $rs
		]);
	}

}


   
}
