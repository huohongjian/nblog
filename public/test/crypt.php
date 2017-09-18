
<script type="text/javascript" src="../public/js/md5.min.js"></script>
<script type="text/javascript" src="../public/js/aes.min.js?v=3.12"></script>
<script type="text/javascript" src="../public/js/pad-zeropadding.js?v=3.12"></script>

<script type="text/javascript">
text = '凭栏知潇雨';

var key = '12345678';
    key = CryptoJS.enc.Utf8.parse(key);

var iv = 'chinafreebsd';// "1234577290ABCDEF1264147890ACAE45";
    iv = CryptoJS.enc.Utf8.parse(iv);

var encrypted = CryptoJS.AES.encrypt(text, key, {
      iv: iv,
      mode:CryptoJS.mode.CBC,
      padding:CryptoJS.pad.ZeroPadding
    }).toString();

console.log(key);
console.log(encrypted);

</script>


<?php

function cryptoJsAesDecrypt($passphrase, $jsonString){
    $jsondata = json_decode($jsonString, true);
    try {
        $salt = hex2bin($jsondata["s"]);
        $iv  = hex2bin($jsondata["iv"]);
    } catch(Exception $e) { return null; }

    $ct = base64_decode($jsondata["ct"]);

echo $ct;

    $concatedPassphrase = $passphrase.$salt;
    $md5 = array();
    $md5[0] = md5($concatedPassphrase, true);
    $result = $md5[0];
    for ($i = 1; $i < 3; $i++) {
        $md5[$i] = md5($md5[$i - 1].$concatedPassphrase, true);
        $result .= $md5[$i];
    }
    $key = substr($result, 0, 32);
    $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);

    print_r($data);
    return json_decode($data, true);
}

/**
* Encrypt value to a cryptojs compatiable json encoding string
*
* @param mixed $passphrase
* @param mixed $value
* @return string
*/

function cryptoJsAesEncrypt($passphrase, $value){
    $salt = '1234'; //openssl_random_pseudo_bytes(8);
    $salted = '';
    $dx = '';
    while (strlen($salted) < 48) {
        $dx = md5($dx.$passphrase.$salt, true);
        $salted .= $dx;
    }
    $key = substr($salted, 0, 32);
    $iv  = substr($salted, 32,16);

    $key = "12345678";
    $iv = 'chinafreebsd';// substr('1234577290ABCDEF1264147890ACAE45',0,16);
    $encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
    $data = array("ct" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "s" => bin2hex($salt));

    return json_encode($data);
}



$key="12345678";
$zhi='凭栏知潇雨';

$zhi=json_decode($zhi,true);

echo($zhi);

$rs=(cryptoJsAesEncrypt($key,$zhi));
//echo "<pre>";
print_r($rs);





 function encrypt($id){
 //   $id=serialize($id);
    $key= '12345678';//"1112121212121212121212";
    $iv = 'chinafreebsd';
    $data= openssl_encrypt($id, 'AES-256-CBC',$key,true,$iv);

    $encrypt=base64_encode($data);
    return $encrypt;
}


function decrypt1($encrypt)
{
    $key = '123454536f667445454d537973576562';// '1112121212121212121212';//解密钥匙   
    $encrypt = json_decode(base64_decode($encrypt), true);
    $iv = base64_decode($encrypt['iv']);
    $decrypt = openssl_decrypt($encrypt['value'], 'AES-256-CBC', $key, 0, $iv);
    $id = unserialize($decrypt);
    if($id){
        return $id;
    }else{
        return 0;
    }
}


echo("<br>");
$en = encrypt('凭栏知潇雨');
print_r($en);



?>



<?php

class OpenSSLAES
{
    /**
     * var string $method 加解密方法，可通过openssl_get_cipher_methods()获得
     */
    protected $method;

    /**
     * var string $secret_key 加解密的密钥
     */
    protected $secret_key;

    /**
     * var string $iv 加解密的向量，有些方法需要设置比如CBC
     */
    protected $iv;

    /**
     * var string $options （不知道怎么解释，目前设置为0没什么问题）
     */
    protected $options; 

    /**
     * 构造函数
     *
     * @param string $key 密钥
     * @param string $method 加密方式
     * @param string $iv iv向量
     * @param mixed $options 还不是很清楚 
     *
     */
    public function __construct($key, $method = 'AES-128-ECB', $iv = 'chinafreebsd', $options = 0)
    {
        // key是必须要设置的
        $this->secret_key = isset($key) ? $key : exit('key为必须项');

        $this->method = $method;

        $this->iv = $iv;

        $this->options = $options;
    }

    /**
     * 加密方法，对数据进行加密，返回加密后的数据
     *
     * @param string $data 要加密的数据
     * 
     * @return string 
     *
     */
    public function encrypt($data)
    {
        return openssl_encrypt($data, $this->method, $this->secret_key, $this->options, $this->iv);
    }

    /**
     * 解密方法，对数据进行解密，返回解密后的数据
     *
     * @param string $data 要解密的数据
     * 
     * @return string 
     *
     */
    public function decrypt($data)
    {
        return openssl_decrypt($data, $this->method, $this->secret_key, $this->options, $this->iv);
    }
}

$aes = new OpenSSLAES('12345678');

$encrypted = $aes->encrypt('凭栏知潇雨');
// KSGYvH0GOzQULoLouXqPJA==
echo '要加密的字符串：凭栏知潇雨<br>加密后的字符串：', $encrypted, '<hr>';

$decrypted = $aes->decrypt($encrypted);

echo '要解密的字符串：', $encrypted, '<br>解密后的字符串：', $decrypted;







class AesJs
{
    /**向量
     * @var string
     */
    private static $iv = "chinafreebsd";//16位
    /**
     * 默认秘钥
     */
    const KEY = '12345678';//16位

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
        $encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, self::$iv);
        return base64_encode($encrypted);
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

//调用
//加密
echo AesJs::encrypt('凭栏知潇雨');
//解密
//AesJs::decrypt('要解密的字符串','秘钥');