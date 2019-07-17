<?php

namespace Draguo\Pay\Gateways\Wechat;

use Draguo\Pay\Contracts\GatewayInterface;
use Draguo\Pay\Events;
use Draguo\Pay\Exceptions\GatewayException;
use Draguo\Pay\Exceptions\InvalidArgumentException;
use Draguo\Pay\Exceptions\InvalidSignException;
use Yansongda\Supports\Collection;

abstract class Gateway implements GatewayInterface
{
    /**
     * Mode.
     *
     * @var string
     */
    protected $mode;

    /**
     * Bootstrap.
     *
     * @author Draguo <me@Draguo.cn>
     *
     * @throws InvalidArgumentException
     */
    public function __construct()
    {
        $this->mode = Support::getInstance()->mode;
    }

    /**
     * Pay an order.
     *
     * @author Draguo <me@Draguo.cn>
     *
     * @param string $endpoint
     * @param array  $payload
     *
     * @return Collection
     */
    abstract public function pay($endpoint, array $payload);

    /**
     * Get trade type config.
     *
     * @author Draguo <me@Draguo.cn>
     *
     * @return string
     */
    abstract protected function getTradeType();

    /**
     * Schedule an order.
     *
     * @author Draguo <me@Draguo.cn>
     *
     * @param array $payload
     *
     * @throws GatewayException
     * @throws InvalidArgumentException
     * @throws InvalidSignException
     *
     * @return Collection
     */
    protected function preOrder($payload): Collection
    {
        $payload['sign'] = Support::generateSign($payload);

        Events::dispatch(Events::METHOD_CALLED, new Events\MethodCalled('Wechat', 'PreOrder', '', $payload));

        return Support::requestApi('pay/unifiedorder', $payload);
    }

    /**
     * Find.
     *
     * @author Draguo <me@Draguo.cn>
     *
     * @param string|array $order
     *
     * @return array
     */
    public function find($order): array
    {
        return [
            'endpoint' => 'pay/orderquery',
            'order'    => is_array($order) ? $order : ['out_trade_no' => $order],
            'cert'     => false,
        ];
    }
}
