<?php

class AesJs
{
    /**向量
     * @var string
     */
    private static $iv = "chinafreebsd";//16位
    /**
     * 默认秘钥
     */
    const KEY = 'chinafreebsd';//16位

    public static function init($iv = '')
    {
        self::$iv = $iv;
    }

    /**
     * 加密字符串
     * @param string $data 字符串
     * @param string $key  加密key
     * @return string
     */
    public static function encrypt($data = '', $key = self::KEY)
    {
//        $encrypted = openssl_encrypt($data, MCRYPT_RIJNDAEL_128, $key, MCRYPT_MODE_CBC, self::$iv);
        $encrypted = openssl_encrypt($data, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, self::$iv);


        return base64_encode($encrypted);

        

//echo '加密: '.base64_encode($encrypted)."\n";
    }

    /**
     * 解密字符串
     * @param string $data 字符串
     * @param string $key  加密key
     * @return string
     */
    public static function decrypt($data = '', $key = self::KEY)
    {
        $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($data), MCRYPT_MODE_CBC, self::$iv);
        return rtrim($decrypted, "\0");
    }
}