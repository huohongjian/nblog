<?php

class Index {
	
   protected $container;

   public function __construct(Interop\Container\ContainerInterface $container) {
       $this->container = $container;
   }
   
   public function index($request, $response, $args) {
        // your code
        // to access items in the container... $this->container->get('');
	$this->container->get('view')->render($response, 'index.html', [
		'name' => 'huohongjian'
	]);
        return $response;
   }
   
   
}
