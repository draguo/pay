<?php

namespace Draguo\Pay\Gateways\Wechat;

use Symfony\Component\HttpFoundation\Request;
use Draguo\Pay\Events;
use Draguo\Pay\Exceptions\GatewayException;
use Draguo\Pay\Exceptions\InvalidArgumentException;
use Draguo\Pay\Exceptions\InvalidSignException;
use Draguo\Pay\Gateways\Wechat;
use Yansongda\Supports\Collection;

class RedpackGateway extends Gateway
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
        $payload['wxappid'] = $payload['appid'];

        if (php_sapi_name() !== 'cli') {
            $payload['client_ip'] = Request::createFromGlobals()->server->get('SERVER_ADDR');
        }

        if ($this->mode === Wechat::MODE_SERVICE) {
            $payload['msgappid'] = $payload['appid'];
        }

        unset($payload['appid'], $payload['trade_type'],
              $payload['notify_url'], $payload['spbill_create_ip']);

        $payload['sign'] = Support::generateSign($payload);

        Events::dispatch(Events::PAY_STARTED, new Events\PayStarted('Wechat', 'Redpack', $endpoint, $payload));

        return Support::requestApi(
            'mmpaymkttransfers/sendredpack',
            $payload,
            true
        );
    }

    /**
     * Get trade type config.
     *
     * @author Draguo <me@Draguo.cn>
     *
     * @return string
     */
    protected function getTradeType(): string
    {
        return '';
    }
}
