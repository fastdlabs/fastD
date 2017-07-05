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
 * Class AbstractEvent
 * @package FastD\Event
 */
abstract class AbstractEvent
{
    /**
     * @var string
     */
    protected $name;

    /**
     * AbstractEvent constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param array $data
     * @return mixed
     */
    abstract public function handle(array $data = []);
}