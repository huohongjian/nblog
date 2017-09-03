<?php

class Index {
	public function method1($request, $respose, $args) {
		$respose->getBody()->write('hello,world!');
	}
	
	public function getName($request, $respose, $args) {
		$respose->getBody()->write('getName');
	}
}
