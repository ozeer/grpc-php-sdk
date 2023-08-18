<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Pot\Proto;

/**
 */
class UserClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \Pot\Proto\RegisterReq $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function Register(\Pot\Proto\RegisterReq $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/User/Register',
        $argument,
        ['\Pot\Proto\RegisterResp', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Pot\Proto\LoginReq $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function Login(\Pot\Proto\LoginReq $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/User/Login',
        $argument,
        ['\Pot\Proto\LoginResp', 'decode'],
        $metadata, $options);
    }

}
