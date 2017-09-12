<?php


class Fn {

	static function userAuth($login, $password) {
		return DB::get('nb_user').where({
			"login" 	=> $login, 
			"password"  => $password
		}).select('count(*)');
	}




}