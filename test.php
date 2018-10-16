<?php 

include "./xsra.php";

$pub_key = file_get_contents('./rsa_public_key.pem');
$pri_key = file_get_contents('./private_key.pem');
$xrsa = new XRsa($pub_key,$pri_key);

$a = [];
for ($i = 0; $i < 1000; $i++) { 
    $a[] = [
        'id'   => $i,
        'name' => 'xiaoming',
        'age'  => 18,
        'address' => 'china'
    ];
}
$arr = [
    'info' => $a
];
$base = $xrsa->publicEncrypt(json_encode($arr)); // 使用公钥加密
echo $base . "\n";
$d = $xrsa->privateDecrypt($base); // 使用私钥解密
var_dump($d);


// -------------------------------------
$rsa = XRsa::createKeys(2048); // 创建密钥对
$obj = new Xrsa($rsa['public_key'],$rsa['private_key']);
$data = [
    'id' => 1,
    'name' => 'xiaoming',
    'address' => 'china'
];
$str = $obj->privateEncrypt(json_encode($arr)); // 私钥加密
$sign = $obj->sign($str); // 加签
if($obj->verify($str,$sign) == 1){ // 验签
    echo $obj->publicDecrypt($str) . PHP_EOL; // 公钥解密
}
