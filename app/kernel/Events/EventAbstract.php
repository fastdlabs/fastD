<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/1/30
 * Time: 上午11:18
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Kernel\Events;

use Dobee\Container\Container;
use Dobee\Database\Connection\ConnectionInterface;

/**
 * Class Controller
 *
 * @package Dobee\Framework\Controller
 */
abstract class EventAbstract
{
    /**
     * @return Container
     */
    public function getContainer()
    {
        return \Make::container();
    }

    /**
     * @param       $event
     * @param       $handle
     * @param array $parameters
     * @return \Dobee\Protocol\Http\Response|string
     */
    public function call($event, $handle, array $parameters = [])
    {
        return \Make::callEvent($event, $handle, $parameters);
    }

    /**
     * Get custom defined helper obj.
     *
     * @param string $helper
     * @param array $parameters
     * @param bool $newInstance
     * @return mixed
     */
    public function get($helper, $parameters = array(), $newInstance = false)
    {
        if (is_string($parameters)) {
            $parameters = $this->getParameters($parameters);
        }

        return \Make::container()->get($helper, $parameters, $newInstance);
    }

    /**
     * get database connection driver
     *
     * @param string $connection
     * @return ConnectionInterface
     */
    public function getConnection($connection = null)
    {
        return \Make::db($connection);
    }

    /**
     * @param $connection
     * @return \Dobee\Storage\StorageInterface
     */
    public function getStorage($connection)
    {
        return \Make::storage($connection);
    }

    /**
     * Get custom config parameters.
     *
     * @param string $name
     * @return mixed
     */
    public function getParameters($name = null)
    {
        return \Make::config($name);
    }

    /**
     * @param       $name
     * @param array $parameters
     * @param bool  $suffix
     * @return string
     */
    public function generateUrl($name, array $parameters = array(), $suffix = false)
    {
        return \Make::url($name, $parameters, $suffix);
    }

    /**
     * render template show page.
     *
     * @param string $template template content string or template file path.
     * @param array $parameters
     * @return string
     */
    public function render($template, array $parameters = array())
    {
        return \Make::render($template, $parameters);
    }

    /**
     * @param     $url
     * @param int $statusCode
     * @param array $headers
     * @return \Dobee\Protocol\Http\RedirectResponse
     */
    public function redirect($url, $statusCode = 302, array $headers = [])
    {
        return \Make::redirect($url, $statusCode, $headers);
    }
}