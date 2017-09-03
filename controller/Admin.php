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
   
   public function contact($request, $response, $args) {
        // your code
        // to access items in the container... $this->container->get('');
        return $response;
   }

   public function install($request, $response, $args) {
	$this->container->get('view')->render($response, 'install.html');


	return $response;
   }

   
}
