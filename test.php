<?php 

include "./xsra.php";

$pub_key = file_get_contents('./rsa_public_key.pem');
$pri_key = file_get_contents('./private_key.pem');
$xrsa = new XRsa($pub_key,$pri_key);

$arr = [
    'info' => [
        'name' => 'xiaoming',
        'age'  => 18,
        'address' => 'china'
    ]
];
$base = $xrsa->publicEncrypt(json_encode($arr)); // 使用公钥加密
echo $base . "\n";
$d = $xrsa->privateDecrypt($base); // 使用私钥解密
var_dump($d);
