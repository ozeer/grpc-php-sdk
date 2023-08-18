<?php
//引入 composer 的自动载加
require __DIR__ . '/vendor/autoload.php';

//用于连接 服务端
$client = new \Pot\Proto\UserClient('127.0.0.1:8088', [
    'credentials' => Grpc\ChannelCredentials::createInsecure()
]);

// 实例化 LoginRequest 请求类
$request = new \Pot\Proto\LoginRequest();
$request->setMobile("18701588471");
$request->setPassword("123456");

// 调用远程服务
$get = $client->Login($request)->wait();

list($reply, $status) = $get;

// $reflection = new ReflectionClass ($reply);
// $methods = $reflection->getMethods();
// var_dump($methods);die;

$access_token = $reply->getAccessToken();
$expire_in = $reply->getExpireIn();

var_dump([
    'access_token' => $access_token,
    'expire_in' => $expire_in
]);
