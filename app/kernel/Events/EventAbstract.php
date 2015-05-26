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
use Dobee\Database\DriverManager;
use Dobee\Http\RedirectResponse;
use Dobee\Template\TemplateEngineInterface;
use Dobee\Http\Response;
use Dobee\Http\JsonResponse;
use Dobee\Http\XmlResponse;

/**
 * Class Controller
 *
 * @package Dobee\Framework\Controller
 */
abstract class EventAbstract
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @param Container $container
     * @return $this
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param       $event
     * @param       $handle
     * @param array $parameters
     * @return array|Response|string
     */
    public function call($event, $handle, array $parameters = [])
    {
        return \Make::event($event, $handle, $parameters);
    }

    /**
     * @param $helper
     * @param array
     * @return mixed
     */
    public function get($helper, array $parameters = array())
    {
        return \Make::helper($helper, $parameters);
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
     * get config parameters.
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
     * @return RedirectResponse
     */
    public function redirect($url, $statusCode = 302, array $headers = [])
    {
        return \Make::redirect($url, $statusCode, $headers);
    }
}