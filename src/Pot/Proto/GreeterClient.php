<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Pot\Proto;

/**
 */
class GreeterClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \Pot\Proto\HelloRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function SayHello(\Pot\Proto\HelloRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/Greeter/SayHello',
        $argument,
        ['\Pot\Proto\HelloReply', 'decode'],
        $metadata, $options);
    }

}
