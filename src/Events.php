<?php

namespace Draguo\Pay;

use Exception;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Draguo <me@Draguo.cn>
 *
 * @method static Event dispatch($eventName, Event $event = null) Dispatches an event to all registered listeners
 * @method static array getListeners($eventName = null) Gets the listeners of a specific event or all listeners sorted by descending priority.
 * @method static int|null getListenerPriority($eventName, $listener) Gets the listener priority for a specific event.
 * @method static bool hasListeners($eventName = null) Checks whether an event has any registered listeners.
 * @method static addListener($eventName, $listener, $priority = 0) Adds an event listener that listens on the specified events.
 * @method static removeListener($eventName, $listener) Removes an event listener from the specified events.
 * @method static addSubscriber(EventSubscriberInterface $subscriber) Adds an event subscriber.
 * @method static removeSubscriber(EventSubscriberInterface $subscriber)
 */
class Events
{
    /**
     * Start pay.
     *
     * @Event("Draguo\Pay\Events\PayStarting")
     */
    const PAY_STARTING = 'Draguo.pay.starting';

    /**
     * Pay started.
     *
     * @Event("Draguo\Pay\Events\PayStarted")
     */
    const PAY_STARTED = 'Draguo.pay.started';

    /**
     * Api requesting.
     *
     * @Event("Draguo\Pay\Events\ApiRequesting")
     */
    const API_REQUESTING = 'Draguo.pay.api.requesting';

    /**
     * Api requested.
     *
     * @Event("Draguo\Pay\Events\ApiRequested")
     */
    const API_REQUESTED = 'Draguo.pay.api.requested';

    /**
     * Sign error.
     *
     * @Event("Draguo\Pay\Events\SignFailed")
     */
    const SIGN_FAILED = 'Draguo.pay.sign.failed';

    /**
     * Receive request.
     *
     * @Event("Draguo\Pay\Events\RequestReceived")
     */
    const REQUEST_RECEIVED = 'Draguo.pay.request.received';

    /**
     * Method called.
     *
     * @Event("Draguo\Pay\Events\MethodCalled")
     */
    const METHOD_CALLED = 'Draguo.pay.method.called';

    /**
     * dispatcher.
     *
     * @var EventDispatcher
     */
    protected static $dispatcher;

    /**
     * Forward call.
     *
     * @author Draguo <me@Draguo.cn>
     *
     * @param string $method
     * @param array  $args
     *
     * @throws Exception
     *
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        return call_user_func_array([self::getDispatcher(), $method], $args);
    }

    /**
     * Forward call.
     *
     * @author Draguo <me@Draguo.cn>
     *
     * @param string $method
     * @param array  $args
     *
     * @throws Exception
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array([self::getDispatcher(), $method], $args);
    }

    /**
     * setDispatcher.
     *
     * @author Draguo <me@Draguo.cn>
     *
     * @param EventDispatcher $dispatcher
     *
     * @return void
     */
    public static function setDispatcher(EventDispatcher $dispatcher)
    {
        self::$dispatcher = $dispatcher;
    }

    /**
     * getDispatcher.
     *
     * @author Draguo <me@Draguo.cn>
     *
     * @return EventDispatcher
     */
    public static function getDispatcher(): EventDispatcher
    {
        if (self::$dispatcher) {
            return self::$dispatcher;
        }

        return self::$dispatcher = self::createDispatcher();
    }

    /**
     * createDispatcher.
     *
     * @author Draguo <me@Draguo.cn>
     *
     * @return EventDispatcher
     */
    public static function createDispatcher(): EventDispatcher
    {
        return new EventDispatcher();
    }
}
