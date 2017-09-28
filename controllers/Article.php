<?php

class Article {

	protected $container;

	public function __construct(Interop\Container\ContainerInterface $container) {
		$this->container = $container;
		
	}

	// 显示文章内容
	function index($request, $response, $args) {
		$id = pg_escape_string($args['articleid']);
		$sql = "UPDATE nb_article SET counter=counter+1 WHERE articleid='$id';
				SELECT a.*, b.name AS username FROM nb_article AS a
				LEFT JOIN nb_user AS b ON a.userid=b.userid
				WHERE a.articleid='$id'";
		$article = DB::getInstance()->query($sql)->fetchOne();

		return $this->container->get('view')->render($response, 'article/index.html', [
			'article'=>$article,
			'user' => [ 'userid'=>$GLOBALS['session']->userid,
						'roleid'=>$GLOBALS['session']->roleid
					  ]
		]);
	}


	function search($request, $response, $args) {
		if ($args['key']) {
			$sql = "SELECT * FROM nb_article WHERE title LIKE '%"
				 . pg_escape_string($args['key'])
				 . "%' ORDER BY artid DESC";
			$rs = DB::getInstance()->query($sql)->fetchAll();
		} else {
			$sql = "SELECT * FROM nb_article ORDER BY artid DESC";
			$rs = DB::getInstance()->query($sql)->fetchAll();
		}
		return $this->container->get('view')->render($response, 'article/search.html', [
			'articles' => $rs
		]);
	}


	public function list($request, $response, $args) {

		$rs = DB::get('nb_article')->selectAll();
		
		$this->container->get('view')->render($response, 'article/list.html',
			array('articles'=>$rs)
		);
		return $response;
	}


   
}
