<?php
class Rsa
{
private static $PRIVATE_KEY = '-----BEGIN RSA PRIVATE KEY-----
MIICXwIBAAKBgQDULoYcBM+567E2imguwivco2SjXhxd8dQ/xPJBl9S3mLy3/QGS
d0dJxN7YtlkAK0q+ovEfeJa87lzWFdMe+JNoI+1nYMoB2R9nfxRZAZODZ514vnL+
Z+fBw/2hpRS1/jWHmpqdyQ6uBZlIzSIvTGnzfyPwhkESzUqK4rT9nsNilwIDAQAB
AoGBAIUDVVcjPQWUV1eVlJIbb1u1olU3nhjWjPQdBrFP+S3PNh8xIFctJyd7nyfD
yC9u9EBl7TqJYhW2Z8RdkigMNHsjWrEBZLFaN5nVz/57T9pagjEW9NpIoI5s/tEf
J0vuHOdK0WEEWZLu3oWaiwSMqf7Off1eHaJecf0mJDgPu42hAkEA+4tF+9WNn6/U
HtrXe/V9TkE61FE7ye84TajmBJ2UVmiWfkBAD75TbN1LNj6ZDuJztte9usYg+ie7
iswrRvcDkwJBANfwvxdncFFoRcCFjISbEoWN60H0ML/ZvHK77qj2RvvQ5WXgT8fB
BG4BLZzEb9VnUyoYuug5dv8APfuONQthz20CQQDu4b7egFn00qghfTays9oCHRRf
WZ3sEdBogAOhUnzy6nQxBZdQ3DCh7C5nH19/sTKu64d0/n+G0YDbOTXIOQEdAkEA
pIhkIaIH+482roVTVuqNR0OmQF+eEWAG7WjyZL0Zwt8dGu25/Bq+lE7DgVJPX8vV
mxqytySp3YxCrfxhwGVrVQJBAMp5axmbBmwrNRPSDkIH9dhatjR8Sj+84VLb0KU1
Bg1BO664jWA6RnPJRsS5UB3Zi3clV7cRn4QcrYbeLQE8mXw=
-----END RSA PRIVATE KEY-----';
private static $PUBLIC_KEY = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDULoYcBM+567E2imguwivco2Sj
Xhxd8dQ/xPJBl9S3mLy3/QGSd0dJxN7YtlkAK0q+ovEfeJa87lzWFdMe+JNoI+1n
YMoB2R9nfxRZAZODZ514vnL+Z+fBw/2hpRS1/jWHmpqdyQ6uBZlIzSIvTGnzfyPw
hkESzUqK4rT9nsNilwIDAQAB
-----END PUBLIC KEY-----';
/**
 * 16进制
 * 公匙: D42E861C04CFB9EBB1368A682EC22BDCA364A35E1C5DF1D43FC4F24197D4B798BCB7FD0192774749C4DED8B659002B4ABEA2F11F7896BCEE5CD615D31EF8936823ED6760CA01D91F677F1459019383679D78BE72FE67E7C1C3FDA1A514B5FE35879A9A9DC90EAE059948CD222F4C69F37F23F0864112CD4A8AE2B4FD9EC36297
 * 密匙: 85035557233D059457579594921B6F5BB5A255379E18D68CF41D06B14FF92DCF361F3120572D27277B9F27C3C82F6EF44065ED3A896215B667C45D92280C347B235AB10164B15A3799D5CFFE7B4FDA5A823116F4DA48A08E6CFED11F274BEE1CE74AD161045992EEDE859A8B048CA9FECE7DFD5E1DA25E71FD2624380FBB8DA1
 */
    /**
    *返回对应的私钥
    */
    private static function getPrivateKey(){
    
        $privKey = self::$PRIVATE_KEY;
         
        return openssl_pkey_get_private($privKey);      
    }
    /**
    *返回对应的公钥
    */
    private static function getPublicKey(){
    
        $pubkey = self::$PUBLIC_KEY;
         
        return openssl_pkey_get_public($pubkey);      
    }
    /**
     * 私钥加密
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function privEncrypt($data)
    {
        if(!is_string($data)){
                return null;
        }           
        return openssl_private_encrypt($data,$encrypted,self::getPrivateKey())? base64_encode($encrypted) : null;
    }
    
    
    /**
     * 私钥解密
     * @param  [type]  $encrypted 密文（二进制格式且base64编码）
     * @param  boolean $fromjs    密文是否来源于JS的RSA加密
     * @return [type]             [description]
     */
    public static function privDecrypt($encrypted, $fromjs = FALSE)
    {
        if(!is_string($encrypted)){
                return null;
        }
        $padding = $fromjs ? OPENSSL_NO_PADDING : OPENSSL_PKCS1_PADDING;  
        if (openssl_private_decrypt(base64_decode($encrypted), $decrypted, self::getPrivateKey(), $padding))  
        {  
            return $fromjs ? trim(strrev($decrypted)) : $decrypted;  
        }  
        return null; 
    }
    /**
     * 私匙加密
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function encrypt($data) {
        if (openssl_public_encrypt($data, $encrypted, self::getPublicKey()))  
            $data = base64_encode($encrypted);  
        else  
            throw new Exception('Unable to encrypt data. Perhaps it is bigger than the key size?');  
  
        return $data;  
    }
}
 

function test() {
    //JS->PHP传输RSA加密解密测试
    $password = $_POST['password'];
    $key = base64_encode(pack("H*", $password));
    echo Rsa::privDecrypt($key,true);

    echo '<br>';
    //PHP->PHP RSA加密解密测试
    $key = Rsa::encrypt('测试中文rsa加密');

    echo Rsa::privDecrypt($key);
}