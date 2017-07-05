<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Event;


/**
 * Class EventDispatcher
 * @package FastD\Event
 */
abstract class AbstractEventDispatcher
{
    /**
     * @var AbstractEvent[]
     */
    protected $events = [];

    /**
     * @param $event
     * @param $handler
     */
    public function addEvent($event, $handler)
    {
        $this->events[$event] = $handler;
    }

    /**
     * @param $event
     * @param array $data
     * @return mixed
     */
    abstract public function emit($event, array $data = []);

    /**
     * @param $event
     * @return AbstractEvent
     */
    protected function findEvent($event)
    {
        if (!isset($this->events[$event])) {
            throw new \RuntimeException(sprintf('Not found event %s', $event));
        }

        return $this->events[$event];
    }
}