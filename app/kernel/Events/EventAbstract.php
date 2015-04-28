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
     * @var DriverManager
     */
    protected $driverManager;

    /**
     * @var TemplateEngineInterface
     */
    protected $templateEngine;

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
     * @param $plugin
     * @param array
     * @return mixed
     */
    public function get($plugin, array $parameters = array())
    {
        return $this->container->get($plugin, $parameters);
    }

    /**
     * get database connection driver
     *
     * @param string $connection
     * @return ConnectionInterface
     */
    public function getConnection($connection = null)
    {
        return $this->container->get('kernel.storage.database', array($this->getParameters('database')))->getConnection($connection);
    }

    /**
     * @param string $connection
     * @return \Dobee\Storage\Redis\Redis
     */
    public function getRedis($connection)
    {
        return $this->container->get('kernel.storage.redis', array($this->getParameters('storage.redis')))->getConnection($connection);
    }

    /**
     * @param string $connection
     * @return \Dobee\Storage\Memcache\Memcache
     */
    public function getMemcache($connection)
    {
        return $this->container->get('kernel.storage.memcache', array($this->getParameters('storage.memcache')))->getConnection($connection);
    }

    /**
     * @param string $connection
     * @return \Dobee\Storage\Memcached\Memcached
     */
    public function getMemcached($connection)
    {
        return $this->container->get('kernel.storage.memcached', array($this->getParameters('storage.memcached')))->getConnection($connection);
    }

    /**
     * @param string $connection
     * @return \Dobee\Storage\SSDB\SSDB
     */
    public function getSSDB($connection)
    {
        return $this->container->get('kernel.storage.ssdb', array($this->getParameters('storage.ssdb')))->getConnection($connection);
    }

    /**
     * @param $connection
     * @return mixed
     * @deprecated
     */
    public function getDisque($connection)
    {
        return $this->container->get('kernel.storage.disque', array($this->getParameters('storage.disque')))->getConnection($connection);
    }

    /**
     * get config parameters.
     *
     * @param string $name
     * @return mixed
     */
    public function getParameters($name = null)
    {
        return $this->container->get('kernel.config')->getParameters($name);
    }

    /**
     * @param       $name
     * @param array $parameters
     * @param bool  $suffix
     * @return string
     */
    public function generateUrl($name, array $parameters = array(), $suffix = false)
    {
        return $this->container->get('kernel.routing')->generateUrl($name, $parameters, $suffix);
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
        return $this->container->get('kernel.template', array($this->container))->getEngine()->render($template, $parameters);
    }

    /**
     * @param     $url
     * @param int $statusCode
     * @return RedirectResponse
     */
    public function redirect($url, $statusCode = 302)
    {
        return new RedirectResponse($url, $statusCode);
    }

    /**
     * @param array  $responses
     * @param int    $statusCode
     * @param array  $headers
     * @param string $format
     * @return Response
     */
    public function response(array $responses = array(), $statusCode = Response::HTTP_OK, array $headers = array(), $format = 'json')
    {
        $responseData = array(
            'status' => $statusCode,
            'message' => isset(Response::$statusTexts[$statusCode]) ? Response::$statusTexts[$statusCode] : 'Internal Server Error',
        );

        if (!empty($responses)) {
            $responseData['body'] = $responses;
        }

        switch (strtolower($format)) {
            case 'json':
                return $this->responseJson($responseData, $statusCode, $headers);
                break;
            case 'xml':
                return $this->responseXml($responseData, $statusCode, $headers);
                break;
            default:
                return $this->responseText($responseData, $statusCode, $headers);
        }
    }

    /**
     * @param array $responseData
     * @param int   $statusCode
     * @param array $headers
     * @return JsonResponse
     */
    public function responseJson(array $responseData, $statusCode = Response::HTTP_OK, array $headers = array())
    {
        return new JsonResponse($responseData, $statusCode, $headers);
    }

    /**
     * @param array $responseData
     * @param int   $statusCode
     * @param array $headers
     * @return XmlResponse
     */
    public function responseXml(array $responseData, $statusCode = Response::HTTP_OK, array $headers = array())
    {
        return new XmlResponse($responseData, $statusCode, $headers);
    }

    /**
     * @param array $responseData
     * @param int   $statusCode
     * @param array $headers
     * @return Response
     */
    public function responseText(array $responseData, $statusCode = Response::HTTP_OK, array $headers = array())
    {
        return new Response($responseData, $statusCode, $headers);
    }
}