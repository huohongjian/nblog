<?php

class Admin {

	protected $container;

	// constructor receives container instance
	public function __construct(Interop\Container\ContainerInterface $container) {
		$this->container = $container;
	}

	public function index($request, $response, $args) {
		// your code
		// to access items in the container... $this->container->get('');
		$this->container->get('view')->render($response, 'admin_index.html', [
			'name' => $args['name']
			]);
		return $response;
	}

	public function userlist($request, $response, $args) {

		$rs = DB::get('nb_user')->select();
		echo ('<br>---------------------------------');
		print_r($rs);
		return $response;
	}

	public function install($request, $response, $args) {
		echo '开始初始化数据库...<br><br><br>';

		$content = "";
		$handle = fopen("..//sql//install.sql", "r");
		while (!feof($handle)) {
			$content .= fread($handle, 4096); //4096B
		}
		fclose($handle);
		$content = str_replace("\r", "\n", $content);
		
		$pgsql = PgSQL::getInstance();
		$pgsql->query($content);
		echo '数据库初始化完毕<br>';


//		$this->container->get('view')->render($response, 'install.html');


		return $response;
	}

   
}
