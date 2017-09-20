<?php

class Article {

	protected $container;

	public function __construct(Interop\Container\ContainerInterface $container) {
		$this->container = $container;
		
	}

	private function getArticleById($id) {
		return DB::get("nb_article")->where(array('articleid'=>$id))->selectOne();
	}


	// 显示文章内容
	function index($request, $response, $args) {
//		$sql = "SELECT * from nb_user WHERE login= $1 ";

		
$where = ['name'=>'hhj', 'id'=>2, 'ord'=>'www'];

echo DB::getInstance()->where($where,[], ['or']);


		return;
		$id = $args['articleid'];
		$article = DB::get("nb_article")->where(array('articleid'=>$id))->selectOne();
		// $this->container->get('view')->render($response, TEMPLATE.'/article/index.html',
		// 	array('article'=>$article)
		// );
		return $response;
	}


	public function list($request, $response, $args) {

		$rs = DB::get('nb_article')->select();
		
		$this->container->get('view')->render($response, TEMPLATE.'/article/list.html',
			array('articles'=>$rs)
		);
		return $response;
	}


	public function edit($request, $response, $args) {
		$userid = Session::get('userid');
		if ($userid) {
			$id = $args['articleid'];
			$article = empty($id) ? array() : $this->getArticleById($id);
			$cats = DB::get('nb_user')->where(['userid'=>$userid])->selectVal(['categories']);
			return $this->container->get('view')->render($response, TEMPLATE.'/article/kindeditor.html', [
				'categories' => explode(',', $cats),
				'article' => $article,
				'maxnum'  => 12,
			]);
		} else {
			return $response->withStatus(302)->withHeader('Location', '/login');
		}
	}


	public function save($request, $response, $args) {

		$p = $request->getParsedBody();
		if (empty($p['articleid'])) {
			$p['articleid'] = uniqid();
		}
		$id = DB::get('nb_article')->returning('articleid')->conflict('articleid')->upsert($p);

		return $response->withJson(['status'=>200, 'articleid'=>$id]);
	}

   
}
