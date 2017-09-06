<?php

class Index {
	
   protected $container;

  function __construct(Interop\Container\ContainerInterface $container) {
       $this->container = $container;
   }
   
   function index($request, $response, $args) {
        // your code
        // to access items in the container... $this->container->get('');
	$this->container->get('view')->render($response, 'index.html', [
		'name' => 'huohongjian'
	]);
        return $response;
   }


   function regist($request, $response, $args) {
	return $response->withStatus(302)->withHeader('Location', '/');
	return $response;
   }


    function doRegist($request, $response, $args) {
	$this->container->get('view')->render($response, 'regist.html', [
		'name' => 'huohongjian'
	]);
	return $response;
   }


      function login($request, $response, $args) {

	return $response;
   }
   
}
