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
     * @param \Pot\Proto\RegisterRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function Register(\Pot\Proto\RegisterRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/User/Register',
        $argument,
        ['\Pot\Proto\SuccessRsp', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Pot\Proto\LoginRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function Login(\Pot\Proto\LoginRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/User/Login',
        $argument,
        ['\Pot\Proto\LoginRsp', 'decode'],
        $metadata, $options);
    }

}
