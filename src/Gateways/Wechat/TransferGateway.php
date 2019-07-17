<?php

namespace Draguo\Pay\Gateways\Wechat;

use Symfony\Component\HttpFoundation\Request;
use Draguo\Pay\Events;
use Draguo\Pay\Exceptions\GatewayException;
use Draguo\Pay\Exceptions\InvalidArgumentException;
use Draguo\Pay\Exceptions\InvalidSignException;
use Draguo\Pay\Gateways\Wechat;
use Yansongda\Supports\Collection;

class TransferGateway extends Gateway
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
        if ($this->mode === Wechat::MODE_SERVICE) {
            unset($payload['sub_mch_id'], $payload['sub_appid']);
        }

        $type = Support::getTypeName($payload['type'] ?? '');

        $payload['mch_appid'] = Support::getInstance()->getConfig($type, '');
        $payload['mchid'] = $payload['mch_id'];

        if (php_sapi_name() !== 'cli' && !isset($payload['spbill_create_ip'])) {
            $payload['spbill_create_ip'] = Request::createFromGlobals()->server->get('SERVER_ADDR');
        }

        unset($payload['appid'], $payload['mch_id'], $payload['trade_type'],
            $payload['notify_url'], $payload['type']);

        $payload['sign'] = Support::generateSign($payload);

        Events::dispatch(Events::PAY_STARTED, new Events\PayStarted('Wechat', 'Transfer', $endpoint, $payload));

        return Support::requestApi(
            'mmpaymkttransfers/promotion/transfers',
            $payload,
            true
        );
    }

    /**
     * Find.
     *
     * @author Draguo <me@Draguo.cn>
     *
     * @param $order
     *
     * @return array
     */
    public function find($order): array
    {
        return [
            'endpoint' => 'mmpaymkttransfers/gettransferinfo',
            'order'    => is_array($order) ? $order : ['partner_trade_no' => $order],
            'cert'     => true,
        ];
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
