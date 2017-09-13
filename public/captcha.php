<?php

error_reporting(E_ALL);

//session_start();
require_once '../libs/Captcha.php';  //先把类包含进来，实际路径根据实际情况进行修改。
require_once '../libs/Session.php';


$_vc = new ValidateCode();  //实例化一个对象
$_vc->doimg();  
//$_SESSION['authnum_session'] = $_vc->getCode();//验证码保存到SESSION中
Session::set(['num'=>$_vc->getCode()]);


// $image=new Captcha(); 
// $image->create();



