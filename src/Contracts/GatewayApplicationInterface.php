<?php

namespace Draguo\Pay\Contracts;

use Symfony\Component\HttpFoundation\Response;
use Yansongda\Supports\Collection;

interface GatewayApplicationInterface
{
    /**
     * To pay.
     *
     * @author Draguo <me@yansonga.cn>
     *
     * @param string $gateway
     * @param array  $params
     *
     * @return Collection|Response
     */
    public function pay($gateway, $params);

    /**
     * Query an order.
     *
     * @author Draguo <me@Draguo.cn>
     *
     * @param string|array $order
     * @param string       $type
     *
     * @return Collection
     */
    public function find($order, $type);

    /**
     * Refund an order.
     *
     * @author Draguo <me@Draguo.cn>
     *
     * @param array $order
     *
     * @return Collection
     */
    public function refund($order);

    /**
     * Cancel an order.
     *
     * @author Draguo <me@Draguo.cn>
     *
     * @param string|array $order
     *
     * @return Collection
     */
    public function cancel($order);

    /**
     * Close an order.
     *
     * @author Draguo <me@Draguo.cn>
     *
     * @param string|array $order
     *
     * @return Collection
     */
    public function close($order);

    /**
     * Verify a request.
     *
     * @author Draguo <me@Draguo.cn>
     *
     * @param string|null $content
     * @param bool        $refund
     *
     * @return Collection
     */
    public function verify($content, $refund);

    /**
     * Echo success to server.
     *
     * @author Draguo <me@Draguo.cn>
     *
     * @return Response
     */
    public function success();
}
