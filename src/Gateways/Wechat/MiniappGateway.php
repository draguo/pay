<?php

namespace Draguo\Pay\Gateways\Wechat;

use Draguo\Pay\Exceptions\GatewayException;
use Draguo\Pay\Exceptions\InvalidArgumentException;
use Draguo\Pay\Exceptions\InvalidSignException;
use Draguo\Pay\Gateways\Wechat;
use Yansongda\Supports\Collection;

class MiniappGateway extends MpGateway
{
    /**
     * Pay an order.
     *
     * @author Draguo <me@Draguo.cn>
     *
     * @param string $endpoint
     * @param array  $payload
     *
     * @throws GatewayException
     * @throws InvalidArgumentException
     * @throws InvalidSignException
     *
     * @return Collection
     */
    public function pay($endpoint, array $payload): Collection
    {
        $payload['appid'] = Support::getInstance()->miniapp_id;

        if ($this->mode === Wechat::MODE_SERVICE) {
            $payload['sub_appid'] = Support::getInstance()->sub_miniapp_id;
            $this->payRequestUseSubAppId = true;
        }

        return parent::pay($endpoint, $payload);
    }
}
