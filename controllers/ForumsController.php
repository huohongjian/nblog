<?php

class ForumsController {

protected $container;
protected $twig;

function __construct(Interop\Container\ContainerInterface $container) {
	$this->container = $container;
	$this->twig = $container->get('view');
}


function index($request, $response, $args) {
	$this->container->get('view')->render($response, 'forums/index.html', [
		'categories'=> DB::ins()->select('nb_category',[],'ORDER BY categoryid','name')->all(),
		'threads'	=> Thread::all(),
	]);
	return $response;
}




}
