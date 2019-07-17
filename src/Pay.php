<?php

namespace Draguo\Pay;

use Exception;
use Draguo\Pay\Contracts\GatewayApplicationInterface;
use Draguo\Pay\Exceptions\InvalidGatewayException;
use Draguo\Pay\Gateways\Alipay;
use Draguo\Pay\Gateways\Wechat;
use Draguo\Pay\Listeners\KernelLogSubscriber;
use Yansongda\Supports\Config;
use Yansongda\Supports\Log;
use Yansongda\Supports\Str;

/**
 * @method static Alipay alipay(array $config) 支付宝
 * @method static Wechat wechat(array $config) 微信
 */
class Pay
{
    /**
     * Config.
     *
     * @var Config
     */
    protected $config;

    /**
     * Bootstrap.
     *
     * @author Draguo <me@Draguo.cn>
     *
     * @param array $config
     *
     * @throws Exception
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);

        $this->registerLogService();
        $this->registerEventService();
    }

    /**
     * Magic static call.
     *
     * @author Draguo <me@Draguo.cn>
     *
     * @param string $method
     * @param array  $params
     *
     * @throws InvalidGatewayException
     * @throws Exception
     *
     * @return GatewayApplicationInterface
     */
    public static function __callStatic($method, $params): GatewayApplicationInterface
    {
        $app = new self(...$params);

        return $app->create($method);
    }

    /**
     * Create a instance.
     *
     * @author Draguo <me@Draguo.cn>
     *
     * @param string $method
     *
     * @throws InvalidGatewayException
     *
     * @return GatewayApplicationInterface
     */
    protected function create($method): GatewayApplicationInterface
    {
        $gateway = __NAMESPACE__.'\\Gateways\\'.Str::studly($method);

        if (class_exists($gateway)) {
            return self::make($gateway);
        }

        throw new InvalidGatewayException("Gateway [{$method}] Not Exists");
    }

    /**
     * Make a gateway.
     *
     * @author Draguo <me@yansonga.cn>
     *
     * @param string $gateway
     *
     * @throws InvalidGatewayException
     *
     * @return GatewayApplicationInterface
     */
    protected function make($gateway): GatewayApplicationInterface
    {
        $app = new $gateway($this->config);

        if ($app instanceof GatewayApplicationInterface) {
            return $app;
        }

        throw new InvalidGatewayException("Gateway [{$gateway}] Must Be An Instance Of GatewayApplicationInterface");
    }

    /**
     * Register log service.
     *
     * @author Draguo <me@Draguo.cn>
     *
     * @throws Exception
     */
    protected function registerLogService()
    {
        $logger = Log::createLogger(
            $this->config->get('log.file'),
            'Draguo.pay',
            $this->config->get('log.level', 'warning'),
            $this->config->get('log.type', 'daily'),
            $this->config->get('log.max_file', 30)
        );

        Log::setLogger($logger);
    }

    /**
     * Register event service.
     *
     * @author Draguo <me@Draguo.cn>
     *
     * @return void
     */
    protected function registerEventService()
    {
        Events::setDispatcher(Events::createDispatcher());

        Events::addSubscriber(new KernelLogSubscriber());
    }
}
