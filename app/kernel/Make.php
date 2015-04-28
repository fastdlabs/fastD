<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/28
 * Time: 下午9:49
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

class Make 
{
    /**
     * @param       $template
     * @param array $parameters
     * @return string
     */
    public static function template($template, array $parameters = array())
    {
        return static::container('kernel.template', array(static::config('template')))->getEngine()->render($template, $parameters);
    }

    /**
     * @param $parameters
     * @return mixed
     */
    public static function config($parameters)
    {
        return static::container('kernel.config')->getParameters($parameters);
    }

    /**
     * @param       $name
     * @param array $parameters
     * @return mixed
     */
    public static function container($name, array $parameters = array())
    {
        return Application::create()->getContainer()->get($name, $parameters);
    }

    /**
     * @param $connection
     * @return \Dobee\Database\Connection\ConnectionInterface
     */
    public static function db($connection)
    {
        return static::container('kernel.database', array(static::config('database')))->getConnection($connection);
    }

    /**
     * @param $connection
     * @return \Dobee\Storage\StorageInterface
     */
    public static function storage($connection)
    {
        return static::container('kernel.storage', array(static::config('storage')))->getConnection($connection);
    }

    /**
     * @param       $name
     * @param array $parameters
     * @param bool  $suffix
     * @return string
     */
    public static function url($name, array $parameters = array(), $suffix = false)
    {
        return static::request()->getBaseUrl() . static::container('kernel.routing')->generateUrl($name, $parameters, $suffix);
    }

    /**
     * @return \Dobee\Http\Request
     */
    public static function request()
    {
        return static::container('kernel.request');
    }

    /**
     * @param      $name
     * @param null $host
     * @param null $path
     * @return string
     */
    public static function asset($name, $host = null, $path = null)
    {
        if (null === $host) {
            try {
                $host = static::config('assets.host');
            } catch (\InvalidArgumentException $e) {
                $host = static::container('kernel.request')->getHttpAndHost();
            }
        }

        if (null === $path) {
            try {
                $path = static::config('assets.path');
            } catch (\InvalidArgumentException $e) {
                $path = static::container('kernel.request')->getBaseUrl();

                if ('' != pathinfo($path, PATHINFO_EXTENSION)) {
                    $path = pathinfo($path, PATHINFO_DIRNAME);
                }
            }
        }

        return $host . str_replace('//', '/', $path . '/' . $name);
    }

    /**
     * @param $content
     * @return mixed
     */
    public static function log($content)
    {
        return static::container('kernel.logger', array(static::config('logger')))->save($content);
    }

    /**
     * @param \Dobee\Http\Request  $request
     * @param \Dobee\Http\Response $response
     * @return mixed
     */
    public static function logRequest(\Dobee\Http\Request $request, \Dobee\Http\Response $response)
    {
        $content = 'request: [ date: %s, path: %s, format: %s, method: %s, ip: %s } response: { date: %s, status: %s ]';

        $content = sprintf($content,
            date('Y-m-d H:i:s', $request->getRequestTimestamp()),
            $request->getPathInfo(),
            $request->getFormat(),
            $request->getMethod(),
            $request->getClientIp(),
            date('Y-m-d H:i:s', $response->getResponseTimestamp()),
            $response->getStatusCode()
        );

        return static::log($content);
    }

    /**
     * @param     $message
     * @param int $code
     * @throws ErrorException
     */
    public static function exception($message, $code = 500)
    {
        throw new ErrorException($message, $code);
    }

    public static function server(array $config)
    {

    }
}