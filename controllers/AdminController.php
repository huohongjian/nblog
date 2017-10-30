<?php

class AdminController {

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
			'users' => DB::ins()->select('nb_user', [], 'ORDER BY userid')->all()
		]);
	return $response;
}


function articles($request, $response, $args) {
	$userid = Session::get('userid');
	$limit  = 10;

	if ($request->isGet()) {
		return $this->container->get('view')->render($response, 'admin/articles.html', [
			'pages' => ['perItem'=>$limit]
		]);

	} else {
		$post  = $request->getParsedBody();
		$page   = $post['page'] ?? 1;
		$offset = ($page-1) * $limit;
		
		if ($request->isPost()) {
			
			$where = DB::where([
				'a.approved' => $post['approved'],
				'a.columns'  => $post['columns'],
				'a.status' 	 => $post['status'],
				'a.category' => $post['category']
			]);

			if (!empty($post['key'])) {
				$where .= empty($where) ? 'WHERE ' : ' AND ';
				$where .= DB::like('a.'.$post['range'], $post['key']);
			}

			$sql = "SELECT count(*) FROM nb_article a $where";
			$SQL = "SELECT a.articleid,a.title,a.status,a.category,a.counter,a.addtime,
						   a.approved, a.columns, b.name AS username
					FROM nb_article a
					LEFT JOIN nb_user b   ON a.userid=b.userid
					$where
					ORDER BY a.artid DESC LIMIT $limit OFFSET $offset";

			return $response->withJson([
				'articles' => DB::ins()->query($SQL)->data(),
				'pages' => ['totItem'=>DB::ins()->query($sql)->val()]
			]);

		} else if ($request->isPut()) {
			$ids = $post['ids'];
			
			if (empty($ids)) {
				$manage = '请选择文档!';

			} else {
				unset($post['ids']);
				$set = DB::struck($post, $exEmpty=true)->upsets;
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
}


function categories($request, $response, $args) {

	if ($request->isGet()) {

		return $this->container->get('view')->render($response, 'admin/categories.html', [
			'categories' => DB::ins()->select('nb_category', [], 'ORDER BY categoryid', 'name')->all()
		]);


	}
}


function donations($request, $response, $args) {
	$limit = 15;

	if ($request->isGet()) {
		$items 	= DB::ins()->select('nb_donation', [], '', 'count(*)')->val();
		return $this->container->get('view')->render($response, 'admin/donations.html', [
			'pages'	=> ['perItem'=>$limit, 'totItem'=>$items],
			'sum'	=> DB::ins()->select('nb_donation', [], '', 'sum(amount)')->val()
		]);

	} else if ($request->isPost()) {
		$page  = $request->getParsedBody()['page'];
		$offset= ((int)$page - 1) * $limit;
		$rs = DB::ins()->select('nb_donation', [], 'ORDER BY donationid DESC LIMIT '
			.$limit.' OFFSET '.$offset, 'donationid,donor,amount,donations,remark,day')->data();
		return $response->withJson(['donations' => $rs]);

	} else if ($request->isPut()) {
		$post = $request->getParsedBody();
		$donationid = (int)$post['donationid'];
		$amount		= (int)$post['amount'];
		$donor		= DB::escape($post['donor']);
		$donations	= DB::escape($post['donations']);
		$remark		= DB::escape($post['remark']);
		$day		= DB::escape($post['day']);
		if ($donationid==-1) {
			$sql = "INSERT INTO nb_donation (donor,amount,donations,remark) VALUES
					($donor, $amount, $donations, $remark) RETURNING donationid";
		} else {
			$sql = "UPDATE nb_donation SET
						donor=$donor, amount=$amount,
						donations=$donations, remark=$remark, day=$day
					WHERE donationid=$donationid
					RETURNING donationid";
		}
		return $response->withJson([
			status => 200,
			donationid => DB::ins()->query($sql)->val()
		]);

	} else if ($request->isDelete()) {
		$donationid = (int)$request->getParsedBody()['donationid'];
		if (DB::ins()->delete('nb_donation',['donationid'=>$donationid])) {
			return $response->getBody()->write('ok');
		} else {
			return $response->getBody()->write('error');
		}

	}

}

function homecontent($request, $response, $args) {

	if ($request->isGet()) {
		$sql = "SELECT a.articleid, a.title, a.status, a.category, a.newtime, b.name AS username
				FROM nb_article AS a
				LEFT JOIN nb_user AS b ON a.userid=b.userid
				WHERE a.category='system-home'
				ORDER BY a.articleid";
		return $this->container->get('view')->render($response, 'admin/homecontent.html', [
			'articles'=>DB::ins()->query($sql)->all()
		]);

	}

}



function homepage($request, $response, $args) {

	if ($request->isGet()) {
		$sql = "SELECT a.articleid, a.title, a.status, a.newtime, b.name AS username
				FROM nb_article AS a
				LEFT JOIN nb_user AS b ON a.userid=b.userid
				WHERE columns='首页栏目'
				ORDER BY articleid";
		return $this->container->get('view')->render($response, 'admin/homepage.html', [
			'articles'=>DB::ins()->query($sql)->all()
		]);

	} else if ($request->isPost()) {
		$post = $request->getParsedBody();

		$sql = "SELECT articleid FROM nb_article WHERE columns='首页栏目'
				ORDER BY articleid DESC LIMIT 1";
		$maxid = DB::ins()->query($sql)->val();
		$n = ((int)substr($maxid, 20) + 10);
		$newid = 'chinafreebsd-column-' . $n;
		$title = 'column-' . $n;
		$userid= Session::get('userid');

		$sql = "INSERT INTO nb_article (articleid, title, userid, columns, status) VALUES
				('{$newid}', '{$title}', {$userid}, '首页栏目', '隐藏')
				RETURNING newtime";
		$newtime = DB::ins()->query($sql)->val();
		$username = DB::ins()->select('nb_user', ['userid'=>$userid], '', 'name')->val();
		
		return $response->withJson([
			'articleid' => $newid,
			'title'		=> $title,
			'username'	=> $username,
			'newtime'	=> $newtime,
			'msg'		=> '文章添加成功，请编辑内容!',
			'status'	=> 200
		]);

	} else if ($request->isPut()) {
		$post = $request->getParsedBody();
		if (DB::ins()->update('nb_article', ['articleid'=>$post['newID']], ['articleid'=>$post['oldID']])) {
			return $response->getBody()->write('文章ID修改成功！');
		}

	} else if ($request->isDelete()) {

	}
}




function install($request, $response, $args) {

	echo '请与管理员联系...';
	return false;



	echo '开始初始化数据库...<br><br><br>';

	$content = "";
	$handle = fopen("..//sql//modify.sql", "r");
	while (!feof($handle)) {
		$content .= fread($handle, 4096); //4096B
	}
	fclose($handle);
	$content = str_replace("\r", "\n", $content);

	DB::ins()->query($content);

	echo '数据库初始化完毕<br>';

	return $response;
}

   
}
