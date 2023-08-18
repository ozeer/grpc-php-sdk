<?php

namespace Pot;

use Pot\Proto\UserClient;
use Pot\Proto\LoginReq;
use Pot\Proto\LoginResp;
use Pot\Proto\RegisterReq;
use Pot\Proto\RegisterResp;

/**
 * @desc 维护grpc method与class与req resp的关系
 */
class ClassMap
{
    public static array $aValidMethods = array(
        'Login' => array(
            'mainClass' => UserClient::class,
            'reqClass' => LoginReq::class,
            'repClass' => LoginResp::class,
            'rpcMethod' => "Login",
            'needArg'  => true,
        ),
        'Register' => array(
            'mainClass' => UserClient::class,
            'reqClass' => RegisterReq::class,
            'repClass' => RegisterResp::class,
            'rpcMethod' => "Register",
            'needArg'  => true,
        ),
    );

    public static function test(): void
    {
        new LoginReq();
        new LoginResp();

        new RegisterReq();
        new RegisterResp();
    }
}
