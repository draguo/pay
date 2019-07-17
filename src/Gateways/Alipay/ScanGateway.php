<?php

namespace Draguo\Pay\Gateways\Alipay;

use Draguo\Pay\Contracts\GatewayInterface;
use Draguo\Pay\Events;
use Draguo\Pay\Exceptions\GatewayException;
use Draguo\Pay\Exceptions\InvalidConfigException;
use Draguo\Pay\Exceptions\InvalidSignException;
use Yansongda\Supports\Collection;

class ScanGateway implements GatewayInterface
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
     * @throws InvalidConfigException
     * @throws InvalidSignException
     *
     * @return Collection
     */
    public function pay($endpoint, array $payload): Collection
    {
        $payload['method'] = 'alipay.trade.precreate';
        $payload['biz_content'] = json_encode(array_merge(
            json_decode($payload['biz_content'], true),
            ['product_code' => '']
        ));
        $payload['sign'] = Support::generateSign($payload);

        Events::dispatch(Events::PAY_STARTED, new Events\PayStarted('Alipay', 'Scan', $endpoint, $payload));

        return Support::requestApi($payload);
    }
}
