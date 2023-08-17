<?php
//引入 composer 的自动载加
require __DIR__ . '/vendor/autoload.php';

//用于连接 服务端
$client = new \Pot\Proto\GreeterClient('127.0.0.1:8088', [
    'credentials' => Grpc\ChannelCredentials::createInsecure()
]);

//实例化 TestRequest 请求类
$request = new \Pot\Proto\HelloRequest();
$request->setName("Alan");

//调用远程服务
$get = $client->SayHello($request)->wait();

list($reply, $status) = $get;

//数组
$data = $reply->getMessage();

var_dump($data);
var_dump($status);
