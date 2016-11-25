<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Controller;

use FastD\Container\Support\ContainerAware;
use FastD\Database\Drivers\DriverInterface;
use FastD\Database\Cache\CacheInterface;
use FastD\Packet\Binary;
use FastD\Storage\StorageInterface;
use FastD\Http\RedirectResponse;
use FastD\Http\JsonResponse;
use FastD\Storage\Storage;
use FastD\Http\Response;
use FastD\Database\Fdb;

/**
 * Class Controller
 *
 * @package FastD\Controller
 */
abstract class Controller
{
    use ContainerAware;

    /**
     * @var Fdb
     */
    protected $fdb;

    /**
     * @var Storage
     */
    protected $storage;

    /**
     * Get custom defined helper obj.
     *
     * @param string $name
     * @param array $parameters
     * @param bool $flag
     * @return mixed
     */
    public function get($name, array $parameters = [], $flag = false)
    {
        return $flag ? $this->getContainer()->instance($name, $parameters) : $this->getContainer()->singleton($name, $parameters);
    }

    /**
     * @param null $connection
     * @return DriverInterface
     */
    public function getDriver($connection = null)
    {
        if (null === $this->fdb) {
            $this->fdb = $this->get('kernel.database', [$this->getParameters('database')]);
        }

        return $this->fdb->getDriver($connection);
    }

    /**
     * @param $connection
     * @return StorageInterface|CacheInterface
     */
    public function getStorage($connection)
    {
        if (null === $this->storage) {
            $this->storage = $this->get('kernel.storage', [$this->getParameters('storage')]);
        }

        return $this->storage->getConnection($connection);
    }

    /**
     * Get custom config parameters.
     *
     * @param string $name
     * @return mixed
     */
    public function getParameters($name)
    {
        return $this->get('kernel.config')->get($name);
    }

    /**
     * @param $name
     * @param array $arguments
     * @return mixed
     */
    public function middleware($name, array $arguments = [])
    {
        return $this->get('kernel.stack')->run($name, $arguments);
    }

    /**
     * @param $name
     * @param array $arguments
     * @return mixed
     */
    public function trigger($name, array $arguments = [])
    {
        return $this->get('kernel.event')->trigger($name, $arguments);
    }

    /**
     * @param       $name
     * @param array $parameters
     * @param string $format
     * @return string
     */
    public function generateUrl($name, array $parameters = array(), $format = '')
    {
        $request = $this->get('kernel.request');

        $path = $this->get('kernel.routing')->generateUrl($name, $parameters, $format);

        $port = '';

        if (!in_array($request->server->getPort(), [443, 80])) {
            $port = ':' . $request->server->getPort();
        }

        return '//' . $request->getHost() . $port . str_replace('//', '/', $request->getBaseUrl() . $path);
    }

    /**
     * @param $name
     * @return string
     */
    public function asset($name)
    {
        $request = $this->get('kernel.request');

        $url = $request->getBaseUrl();

        $baseUrl = $request->getHost() . ('' == pathinfo($url, PATHINFO_EXTENSION) ? $url : pathinfo($url, PATHINFO_DIRNAME));

        $baseUrl = ltrim($baseUrl, '/');

        return '//' . $baseUrl . '/bundles/' . $name;
    }

    /**
     * @param $name
     * @param array $parameters
     * @return  Response
     */
    public function forward($name, array $parameters = [])
    {
        $route = $this->get('kernel.routing')->getRoute($name);

        $route->mergeParameters($parameters);

        return $this->handleRoute($route);

    }

    /**
     * Render template to html or return content.
     *
     * @param            $view
     * @param array $parameters
     * @param bool|false $flag
     * @return Response|string
     */
    public function render($view, array $parameters = array(), $flag = false)
    {
        restore_error_handler();
        restore_error_handler();

        $content = $this->get('kernel.twig')->render($view, $parameters);

        return $flag ? $content : $this->responseHtml($content);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function dump($data)
    {
        return $this->get('kernel.debug')->dump($data);
    }

    /**
     * Redirect url.
     *
     * @param       $url
     * @param int $statusCode
     * @param array $headers
     * @return RedirectResponse
     */
    public function redirect($url, $statusCode = 302, array $headers = [])
    {
        return new RedirectResponse($url, $statusCode, $headers);
    }

    /**
     * @param       $data
     * @param int $status
     * @param array $headers
     * @return Response
     */
    public function responseHtml($data, $status = Response::HTTP_OK, array $headers = [])
    {
        return new Response($data, $status, $headers);
    }

    /**
     * @param array $data
     * @param int $status
     * @param array $headers
     * @return JsonResponse
     */
    public function responseJson(array $data, $status = Response::HTTP_OK, array $headers = [])
    {
        return new JsonResponse($data, $status, $headers);
    }

    /**
     * @param array $data
     * @param $status
     * @return Response
     */
    public function responseBinary(array $data, $status)
    {
        return $this->responseHtml(Binary::encode($data), $status);
    }
}
