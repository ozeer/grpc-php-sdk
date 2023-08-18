<?php

declare(strict_types=1);

namespace Unit;

use PHPUnit\Framework\TestCase;

class SendTest extends TestCase
{
    public function send(): void
    {
        var_dump("send rpc request");
    }
}
