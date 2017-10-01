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

	if ($request->isGet()) {

		return $this->container->get('view')->render($response, 'admin/articles.html', [

		]);


	} else {

	}

	$page = 1;
	$limit = 3;
	$sql = 'SELECT a.articleid, a.title, b.name AS username, a.status, a.category, c.name AS columnname, a.addtime
				   
			FROM nb_article a
			LEFT JOIN nb_user b ON a.userid=b.userid
			LEFT JOIN nb_column c ON a.columnid=c.columnid';
	$SQL = 'SELECT count(a.*) FROM nb_article a';

	if ($request->isPost()) {
		$post = $request->getParsedBody();
		$page = (int)$post['page'];

		if (!empty($post['status'])) {
			$where .= ' AND a.status='.DB::escape($post['status']);
		}
		if (!empty($post['columnid'])) {
			$where .= ' AND a.columnid='.DB::clear($post['columnid']);
		}
		if (!empty($post['key'])) {
			$where .= ' AND a.' . DB::clear($post['range']) .
					  ' LIKE ' . DB::escape("%{$post['key']}%");
		}
	}

	if ($where) {
		$where = ' WHERE ' . substr($where, 4);
		$sql .= $where;
		$SQL .= $where;
	}
	$sql .= " ORDER BY a.artid DESC LIMIT $limit OFFSET " . ($page - 1) * $limit;

	$pages = [	'totItem'=>DB::getInstance()->query($SQL)->val(), 
				'perItem'=>$limit,
				'curPage'=>$page
			];
	$articles = DB::getInstance()->query($sql)->arr();


	if ($request->isPost()) {
		return $response->withJson([
			'articles' => $articles,
			'pages' => $pages
		]);
	} else {
		
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
