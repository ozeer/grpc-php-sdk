<?php
//引入 composer 的自动载加
use Pot\Log;

require __DIR__ . '/vendor/autoload.php';

Log::info("record", ['home' => 'miss', 'year' => 'past']);
