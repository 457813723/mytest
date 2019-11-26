<?php
/*
 *
 * RSA加密解密
 *
 * */

class RSA{
    private $config;
    function __construct()
    {
        $this->config = array(
            "digest_alg" => "sha512",
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
//					'config'=>'/Public/ApiVone/ssl/openssl.cnf'
//					'config'=>'D:\phpStudy\Apache\conf\openssl.cnf'
            'config'=>'./openssl.cnf'
        );


    }

    //创建公钥密钥
    public function createKey($bits = 2048, $timeout = false, $partial = array())
    {

        $res = openssl_pkey_new($this->config);


        openssl_pkey_export($res, $private_key,null,$this->config);

        $details = openssl_pkey_get_details($res);
        $public_key = $details["key"];
        return array('public_key'=>$public_key,'private_key'=>$private_key);
    }


    //公钥加密
    public function encrypt_by_public_base64($data,$public_key){
        if(!$data || !$public_key){
            return FALSE;
        }
        $crypted = '';
        $public_key =  openssl_pkey_get_public($public_key);
        for ($i = 0; $i < strlen($data); $i += 117) {
            $src = substr($data, $i, 117);
            $ret = openssl_public_encrypt($src, $out, $public_key);
            $crypted .= $out;
        }
        return 	base64_encode($crypted);

    }

    //私钥解密
    public function decrypt_by_private($crypted,$private_key)
    {
        if(!$crypted || !$private_key){
            return FALSE;
        }
        $out_plain = '';
        $private_key = openssl_get_privatekey($private_key);
        for ($i = 0; $i < strlen($crypted); $i += 256) {
            $src = substr($crypted, $i, 256);
            $ret = openssl_private_decrypt($src, $out, $private_key);
            //var_dump($private_key);
            $out_plain .= $out;
        }

        return $out_plain;
    }

    //私钥加签
    public function sign_by_private_key_base64_en($data,$private_key){
        if(!$data || !$private_key){
            return FALSE;
        }
        $private_key = openssl_pkey_get_private($private_key);
        openssl_sign($data,$sign,$private_key);
        openssl_free_key($private_key);
        $sign = base64_encode($sign);//最终的签名
        return $sign;
    }

    //公钥验签
    public function verify_by_public_key($original_str,$sign,$public_key)
    {
        if(!$original_str || !$sign || !$public_key){
            return FALSE;
        }
        $public_key = openssl_get_publickey($public_key);
        $sign=base64_decode($sign);//得到的签名
        $result=(bool)openssl_verify($original_str,$sign,$public_key);
        openssl_free_key($public_key);
        return $result;

    }


}











?>