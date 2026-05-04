<?php
//API接口
$url = "http://yqtj2.wnu.edu.cn/index.php/1/addons/fire/up/submit";
$header = [
'Host: yqtj2.wnu.edu.cn',
'Connection: keep-alive',
'Content-Length: 222',
'PAGE: /pages/index/ding',
'LANGUAGE: zh',
'User-Agent: Mozilla/5.0 (Linux; Android 12; KB2000 Build/RKQ1.211119.001; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/86.0.4240.99 XWEB/4317 MMWEBSDK/20220903 Mobile Safari/537.36 MMWEBID/6263 MicroMessenger/8.0.28.2240(0x28001C57) WeChat/arm64 Weixin NetType/WIFI Language/zh_CN ABI/arm64',
'X_REQUESTED_WITH: xmlhttprequest',
'Header-Pool: {"UNIACID":1,"X_REQUESTED_WITH":"xmlhttprequest","CURRENT-SHOP":null,"PAGE":"/pages/index/ding","ADDONS":"fire","LANGUAGE":"zh","Client-Container":"wechat","OAUTH-CODE":"0CF5291A-114C-83FB-609F-9589684EAE06"}',
'Content-Type: application/json',
'UNIACID: 1',
'CURRENT-SHOP: null',
'ADDONS: fire',
'Accept: */*',
'Origin: http://yqtj2.wnu.edu.cn',
'X-Requested-With: com.tencent.mm',
'Referer: http://yqtj2.wnu.edu.cn/addons/fire/h5/',
'Accept-Encoding: gzip, deflate',
'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7',
'Cookie: OAUTH_CODE=你的OAUTH_CODE'
];
//全部健康
$message = '{"data":{"whereis":1,"health_state":1,"family_health":1,"fever":0,"fifteen_covid":0,"village_covid":0,"temperature":null,"temp_state":1,"work":1,"back":0,"lng":null,"lat":null,"agent_up":0,"agent_user_id":null,"parent":0}}';
function post(){
  global $url;
  global $header;
  global $message;
  $ch = curl_init();//初始化cURL
  curl_setopt($ch,CURLOPT_URL,$url);//访问指定网页
  curl_setopt($ch,CURLOPT_HTTPHEADER,$header);//设置header
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//要求结果为字符串并输出到屏幕上
  //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, '0');//无视证书
  //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, '0');
  curl_setopt($ch,CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
  curl_setopt($ch,CURLOPT_POST,1);//Post请求方式
  curl_setopt($ch,CURLOPT_POSTFIELDS,$message);//Post内容
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}
//方糖微信公众号提示APP
function sc_send(  $text , $desp = '' , $key = '获取的KEY'  )
{
    $postdata = http_build_query( array( 'text' => $text, 'desp' => $desp ));
    $opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $postdata));
    $context  = stream_context_create($opts);
    return $result = file_get_contents('https://sctapi.ftqq.com/'.$key.'.send', false, $context);
}
//上传云函数执行
//cron表达式	0 15 0,12 * * * *
//执行时间：
//每天 00:15:00（凌晨 0 点 15 分 0 秒）
//每天 12:15:00（中午 12 点 15 分 0 秒）
function main_handler($event, $context) {
    $data = post();
    sc_send("签到提醒",$data);
    return $data;
}
?>
