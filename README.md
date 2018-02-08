# rsa-node-php
该模块可以使用nodejs进行加密PHP解密或PHP加密nodejs解密

### node.js
<pre>
<code>
let rsa = require('./xsra')
let obj = {
    'info':{
        'name':'xiaoming',
        'age':18,
        'address':'China'
    }
};
console.dir(rsa.entry(obj)); // 使用公钥加密
console.dir(rsa.decrypt(rsa.entry(obj))); // 使用私钥解密
</code>
</pre>


### php
```

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

```

### linux使用openssl生成rsa密钥对
```
openssl genrsa -out rsa_private_key.pem 1024   生成1024位的私钥

openssl pkcs8 -topk8 -inform PEM -in rsa_private_key.pem -outform PEM -nocrypt -out private_key.pem  转换私钥位pkcs8模式 输出到private_key.pem

openssl rsa -in rsa_private_key.pem -pubout -out rsa_public_key.pem   通过私钥生成公钥
```