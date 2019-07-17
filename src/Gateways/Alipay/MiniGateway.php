<?php

namespace Draguo\Pay\Gateways\Alipay;

use Draguo\Pay\Contracts\GatewayInterface;
use Draguo\Pay\Events;
use Draguo\Pay\Exceptions\GatewayException;
use Draguo\Pay\Exceptions\InvalidArgumentException;
use Draguo\Pay\Exceptions\InvalidConfigException;
use Draguo\Pay\Exceptions\InvalidSignException;
use Yansongda\Supports\Collection;

class MiniGateway implements GatewayInterface
{
    /**
     * Pay an order.
     *
     * @author xiaozan <i@xiaozan.me>
     *
     * @param string $endpoint
     * @param array  $payload
     *
     * @throws GatewayException
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     * @throws InvalidSignException
     *
     * @link https://docs.alipay.com/mini/introduce/pay
     *
     * @return Collection
     */
    public function pay($endpoint, array $payload): Collection
    {
        if (empty(json_decode($payload['biz_content'], true)['buyer_id'])) {
            throw new InvalidArgumentException('buyer_id required');
        }

        $payload['method'] = 'alipay.trade.create';
        $payload['sign'] = Support::generateSign($payload);

        Events::dispatch(Events::PAY_STARTED, new Events\PayStarted('Alipay', 'Mini', $endpoint, $payload));

        return Support::requestApi($payload);
    }
}
