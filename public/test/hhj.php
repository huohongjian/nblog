<?php


function make_coupon_card() {  
       mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.  
       $charid = strtoupper(md5(uniqid(rand(), true)));  
        $hyphen = chr(45);// "-"  
       $uuid = //chr(123)// "{"  
               substr($charid, 0, 8).$hyphen  
               .substr($charid, 8, 4).$hyphen  
              .substr($charid,12, 4).$hyphen  
                .substr($charid,16, 4).$hyphen  
               .substr($charid,20,12);  
               //.chr(125);// "}"  
      return $uuid;  
  
}  



echo(make_coupon_card()."<br>");





 
function generate_password( $length = 12 ) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    for ( $i = 0; $i < $length; $i++ ) 
    {
        $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }
    return $password;
}
echo generate_password(12);
