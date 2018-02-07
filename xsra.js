let NodeRSA = require('node-rsa');
let fs = require('fs');
let public_key = new NodeRSA(fs.readFileSync(__dirname + '/rsa_public_key.pem'));
// 设置加密使用pkcs1 默认是用pkcs1_oaep
public_key.setOptions({encryptionScheme: 'pkcs1'});
let private_key = new NodeRSA(fs.readFileSync(__dirname + '/private_key.pem'));
private_key.setOptions({encryptionScheme: 'pkcs1'});

function url_safe_base64_encode(data)
{
    data = data.replace(/\+/g,'-');
    data = data.replace(/\//g,'_');
    data = data.replace(/=/g,'');
    return data;
}

function url_safe_base64_decode(data)
{
    data = data.replace(/-/g,'+');
    data = data.replace(/_/g,'/');
    data = (data.substring(data.length-1) == '|') ? data.substring(0,data.length-1) : data;
    data = data.split('|');
    return data;
}

function entry(msg) 
{
    if(typeof(msg) == 'object'){
        msg = JSON.stringify(msg);
    }
    
    let par_len = 1024 / 8 - 11;
    let arr_msg = strsplit(msg,par_len);
    let encrypted = '';
    for (var k in arr_msg) {
        encrypted += public_key.encrypt(arr_msg[k],'base64') + '|';
    }
    return url_safe_base64_encode(encrypted);
}

function strsplit(msg,length)
{
    if(msg.length <= length){
        return [msg]
    }
    let j = 0;
    let arr = []
    for(var i = 0; i <= msg.length;i += length){
        arr[j] = msg.slice(i,i + length);
        j++;
    }
    return arr;
}

function decrypt(data)
{
    data = url_safe_base64_decode(data)
    let msg = '';
    for(var i = 0; i < data.length;i++){
        msg += private_key.decrypt(data[i],'utf8');
    }
    return msg;
}
module.exports = {
    entry:entry,
    decrypt:decrypt
}
