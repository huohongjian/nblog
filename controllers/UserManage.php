<?php

class UserManage {

	protected $container;

	public function __construct(Interop\Container\ContainerInterface $container) {
		$this->container = $container;
		
	}


	function index($request, $response, $args) {

		$this->container->get('view')->render($response, 'user/manage/index.html',[
			
		]);
		return $response;
	}


	public function userinfo($request, $response, $args) {
		
		$this->container->get('view')->render($response, 'user/manage/userinfo.html',[
			
		]);
		return $response;
	}


	public function template($request, $response, $args) {

		$this->container->get('view')->render($response, 'user/manage/template.html',[
			
		]);
		return $response;
	}


	public function articles($request, $response, $args) {

		$this->container->get('view')->render($response, 'user/manage/articles.html',[
			
		]);
		return $response;
	}


	public function category($request, $response, $args) {

		$this->container->get('view')->render($response, 'user/manage/category.html',[
			
		]);
		return $response;
	}


   
}
