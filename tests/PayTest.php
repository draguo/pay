<?php

namespace Draguo\Pay\Tests;

use Draguo\Pay\Contracts\GatewayApplicationInterface;
use Draguo\Pay\Exceptions\GatewayException;
use Draguo\Pay\Pay;

class PayTest extends TestCase
{
    public function testAlipayGateway()
    {
        $alipay = Pay::alipay(['foo' => 'bar']);

        $this->assertInstanceOf(GatewayApplicationInterface::class, $alipay);
    }

    public function testWechatGateway()
    {
        $wechat = Pay::wechat(['foo' => 'bar']);

        $this->assertInstanceOf(GatewayApplicationInterface::class, $wechat);
    }

    public function testFooGateway()
    {
        $this->expectException(GatewayException::class);
        $this->expectExceptionMessage('Gateway [foo] Not Exists');

        Pay::foo([]);
    }
}
