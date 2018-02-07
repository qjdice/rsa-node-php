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
