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
