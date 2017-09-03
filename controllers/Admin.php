<?php

class Admin {
	
   protected $container;

   // constructor receives container instance
   public function __construct(Interop\Container\ContainerInterface $container) {
       $this->container = $container;
   }
   
   public function home($request, $response, $args) {
        // your code
        // to access items in the container... $this->container->get('');
		$this->container->get('view')->render($response, 'profile.html', [
	'name' => $args['name']
	]);
        return $response;
   }
   
   public function contact($request, $response, $args) {
        // your code
        // to access items in the container... $this->container->get('');
        return $response;
   }
}
