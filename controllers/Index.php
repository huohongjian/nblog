<?php

class Index {

protected $container;

function __construct(Interop\Container\ContainerInterface $container) {
	$this->container = $container;
}

function index($request, $response, $args) {
	$fields = 'thumb, content';
	$sql = "SELECT thumb, content FROM nb_article
			WHERE columns='首页栏目' AND status<>'删除'
			ORDER BY articleid";
	$this->container->get('view')->render($response, 'index/index.html', [
		'categories'=> DB::ins()->select('nb_category',[],'ORDER BY categoryid','name')->all(),
		'columns'	=> DB::ins()->query($sql)->all()
	]);
	return $response;
}


function captcha($request, $response, $args) {
	$cap = new Captcha();
	$cap->create();
	Session::set(['ca' => $cap->checkcode]);
	return $response;
}


function checkLoginName($request, $response, $args) {
	$post = $request->getParsedBody();
	if (DB::ins()->select('nb_user',['login'=>$post['login']],'','count(*)')->val()) {
		echo '存在同名用户!';
	}
	return $response;
}

function regist($request, $response, $args) {
	if ($request->isPost()) {
		$post = $request->getParsedBody();
		if (empty($post['name'])) {
			$message = '用户名不得为空!';
		} elseif (strlen($post['login'])<3) {
			$message = '登录名长度不得小于3位!';
		} elseif (strtoupper($post['captcha']) != strtoupper(Session::get('ca'))) {
			$message = '验证码错误!';
		} elseif (DB::ins()->select('nb_user',['login'=>$post['login']],'','count(*)')->val()) {
			$message = '存在同名用户!';
		} else {
			unset($post['captcha']);
			$key = base64_encode(pack("H*", $post['password']));
			$post['password'] = Rsa::privDecrypt($key, true);
			$categories=DB::ins()->select('nb_category',[],'ORDER BY categoryid','name')->vals();
			$post['categories'] = implode(',', $categories);

			if ( DB::ins()->insert('nb_user', $post)->affectedRows() ) {
				return $response->withStatus(302)->withHeader('Location', '/login');
			}
		}
	}
	return $this->container->get('view')->render($response, 'index/regist.html', [
		'errorMessage' => $message
	]);
}


function login($request, $response, $args) {
	if ($request->isPost()) {
		$post = $request->getParsedBody();

		if (strtoupper($post['captcha']) == strtoupper(Session::get('ca'))) {
			$user = DB::ins()->select('nb_user', ["login" => $post['login']])->one();
			if ($user) {
				$key = base64_encode(pack("H*", $post['password']));
				$post['password'] = Rsa::privDecrypt($key, true);
				if ($post['password'] == md5($post['captcha'].$user['password'].$post['captcha'])) {
					Session::set([
						'userid' => (int)$user['userid'],
						'roleid' => (int)$user['roleid'],
						'login'  => $user['login'],
						'name'	 => $user['name']
					]);
					return $response->withStatus(302)->withHeader('Location', '/user');
				} else {
					$message = '登录密码不正确!';
				}
			} else {
				$message = '登录名不存在!';
			}
		} else {
			$message = '验证码错误!';
		}
	}
	return $this->container->get('view')->render($response, 'index/login.html', [
		'errorMessage' => $message
	]);
}


function logout($request, $response, $args) {
	Session::clear();
	return $response->withStatus(302)->withHeader('Location', '/');
}


function suggest($request, $response, $args) {
	if ($request->isPost()) {
		$post = $request->getParsedBody();
		DB::ins()->insert('nb_suggest', $post);
		return $response->getBody()->write('感谢您的宝贵建议!');
	} else {
		$sql = 'SELECT * FROM nb_suggest ORDER BY suggestid DESC';
		return $this->container->get('view')->render($response, 'index/suggest.html', [
			'suggests' => DB::ins()->query($sql)->all()
		]);
	}
	
}


function donation($request, $response, $args) {
	$limit = 15;

	if ($request->isGet()) {
		$items 	= DB::ins()->select('nb_donation', [], '', 'count(*)')->val();
		return $this->container->get('view')->render($response, 'index/donation.html', [
			'pages'	=> ['perItem'=>$limit, 'totItem'=>$items],
			'sum'	=> DB::ins()->select('nb_donation', [], '', 'sum(amount)')->val()
		]);

	} else if ($request->isPost()) {
		$page  = $request->getParsedBody()['page'];
		$offset= ((int)$page - 1) * $limit;
		$rs = DB::ins()->select('nb_donation', [], 'ORDER BY donationid DESC LIMIT '
			.$limit.' OFFSET '.$offset, 'donor,amount,donations,remark,day')->rows();

		return $response->withJson(['donations' => $rs]);
	}
	
}


}
