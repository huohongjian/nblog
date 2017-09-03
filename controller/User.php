<?php

class User
{
    protected $view;
    
    public function __construct(\Slim\Views\Twig $view) {
        $this->view = $view;
    }
    public function home($request, $response, $args) {
      // your code here
      // use $this->view to render the HTML
      $this->view->render($response, 'profile.html', [
	'name' => 'huohongjian'
	]);
      return $response;
    }
}
