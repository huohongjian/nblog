<?php

class Admin {

protected $container;

// constructor receives container instance
public function __construct(Interop\Container\ContainerInterface $container) {
	$this->container = $container;
}

public function index($request, $response, $args) {
	$this->container->get('view')->render($response, 'admin/index.html', [
		'name' => $args['name']
		]);
	return $response;
}

public function users($request, $response, $args) {

	$this->container->get('view')->render($response, 'admin/users.html', [
		'users' => DB::get('nb_user')->order('userid')->select()->all()
		]);
	return $response;
}


function articles($request, $response, $args) {
	$userid = (int)$GLOBALS['session']->userid;

	if ($request->isGet()) {

		return $this->container->get('view')->render($response, 'admin/articles.html', [

		]);


	} else {
		$input  = $request->getParsedBody();
		$page   = $input['page'] ?? 1;
		$limit  = 10;
		$offset = ($page-1) * $limit;
		
		if ($request->isPost()) {
			
			$where = DB::struck([
				'a.approved' => $input['approved'],
				'a.columnid' => $input['columnid'],
				'a.status' 	 => $input['status'],
				'a.category' => $input['category']
			], 'AND')->kvs;
			$where .= DB::like(' AND a.'.$input['range'], $input['key']);

			if ($where) $where = 'WHERE '.$where;
			

			$sql = "SELECT count(*) FROM nb_article a $where";
			$SQL = "SELECT a.articleid,a.title,a.status,a.category,a.counter,a.addtime,
						   a.approved, b.name, c.name AS username
					FROM nb_article a
					LEFT JOIN nb_column b ON a.columnid=b.columnid
					LEFT JOIN nb_user c   ON a.userid=c.userid
					$where
					ORDER BY a.artid DESC LIMIT $limit OFFSET $offset";

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
}




public function install($request, $response, $args) {
	echo '开始初始化数据库...<br><br><br>';

	$content = "";
	$handle = fopen("..//sql//install.sql", "r");
//		$handle = fopen("..//sql//article.sql", "r");
	while (!feof($handle)) {
		$content .= fread($handle, 4096); //4096B
	}
	fclose($handle);
	$content = str_replace("\r", "\n", $content);

	DB::getInstance()->query($content);

	echo '数据库初始化完毕<br>';


//		$this->container->get('view')->render($response, 'install.html');


	return $response;
}

   
}
