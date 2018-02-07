<?php 
if (! function_exists('url_safe_base64_encode')) {
    function url_safe_base64_encode ($data) {
        return str_replace(array('+','/', '='),array('-','_', ''), base64_encode($data) . '|');
    }
}

if (! function_exists('url_safe_base64_decode')) {
    function url_safe_base64_decode ($data) {
        $arr = explode('|',rtrim($data,'|'));
        $base_64 = '';
        foreach($arr as $a){
            $base_64 .= base64_decode(str_replace(array('-','_'),array('+','/'), $a));
        }
        return $base_64;
    }
}

class XRsa
{
    const CHAR_SET = "UTF-8";
    const BASE_64_FORMAT = "UrlSafeNoPadding";
    const RSA_ALGORITHM_KEY_TYPE = OPENSSL_KEYTYPE_RSA;
    const RSA_ALGORITHM_SIGN = OPENSSL_ALGO_SHA256;

    protected $public_key;
    protected $private_key;
    protected $key_len;

    public function __construct($pub_key, $pri_key = null)
    {
        $this->public_key = $pub_key;
        $this->private_key = $pri_key;

        $pub_id = openssl_get_publickey($this->public_key);
        $this->key_len = openssl_pkey_get_details($pub_id)['bits'];
    }

    /*
     * 创建密钥对
     */
    public static function createKeys($key_size = 2048)
    {
        $config = array(
            "private_key_bits" => $key_size,
            "private_key_type" => self::RSA_ALGORITHM_KEY_TYPE,
        );
        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $private_key);
        $public_key_detail = openssl_pkey_get_details($res);
        $public_key = $public_key_detail["key"];

        return [
            "public_key" => $public_key,
            "private_key" => $private_key,
        ];
    }

    /*
     * 公钥加密
     */
    public function publicEncrypt($data)
    {
        $encrypted = '';
        $part_len = $this->key_len / 8 - 11;
        $parts = str_split($data, $part_len);
        foreach ($parts as $part) {
            $encrypted_temp = '';
            openssl_public_encrypt($part, $encrypted_temp,$this->public_key,OPENSSL_PKCS1_PADDING);
            $encrypted .= url_safe_base64_encode($encrypted_temp);
        }
        return $encrypted;
    }

    /*
     * 私钥解密
     */
    public function privateDecrypt($encrypted)
    {
        $decrypted = "";
        $part_len = $this->key_len / 8;
        $base64_decoded = url_safe_base64_decode($encrypted);
        $parts = str_split($base64_decoded, $part_len);
        foreach ($parts as $part) {
            $decrypted_temp = '';
            openssl_private_decrypt($part, $decrypted_temp,$this->private_key,OPENSSL_PKCS1_PADDING);
            $decrypted .= $decrypted_temp;
        }
        return $decrypted;
    }

    /*
     * 私钥加密
     */
    public function privateEncrypt($data)
    {
        $encrypted = '';
        $part_len = $this->key_len / 8 - 11;
        $parts = str_split($data, $part_len);

        foreach ($parts as $part) {
            $encrypted_temp = '';
            openssl_private_encrypt($part, $encrypted_temp, $this->private_key,OPENSSL_PKCS1_PADDING);
            $encrypted .= url_safe_base64_encode($encrypted_temp);
        }

        return $encrypted;
    }

    /*
     * 公钥解密
     */
    public function publicDecrypt($encrypted)
    {
        $decrypted = "";
        $part_len = $this->key_len / 8;
        $base64_decoded = url_safe_base64_decode($encrypted);
        $parts = str_split($base64_decoded, $part_len);

        foreach ($parts as $part) {
            $decrypted_temp = '';
            openssl_public_decrypt($part, $decrypted_temp,$this->public_key,OPENSSL_PKCS1_PADDING);
            $decrypted .= $decrypted_temp;
        }
        return $decrypted;
    }

    /*
     * 数据加签
     */
    public function sign($data)
    {
        openssl_sign($data, $sign, $this->private_key, self::RSA_ALGORITHM_SIGN);

        return url_safe_base64_encode($sign);
    }

    /*
     * 数据签名验证
     */
    public function verify($data, $sign)
    {
        $pub_id = openssl_get_publickey($this->public_key);
        $res = openssl_verify($data, url_safe_base64_decode($sign), $pub_id, self::RSA_ALGORITHM_SIGN);

        return $res;
    }
}
