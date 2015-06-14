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

/**
 * Make class.
 *
 * Make some action.
 */
class Make 
{
    /**
     * @param      $name
     * @param null $value
     * @return \Dobee\Protocol\Http\Session\Session
     */
    public static function session($name, $value = null)
    {
        if (null === $value) {
            return static::request()->getSession()->getSession($name);
        }

        return static::request()->getSession()->setSession($name, $value);
    }

    /**
     * @param        $name
     * @param null   $value
     * @param int    $expire
     * @param string $path
     * @return \Dobee\Protocol\Http\Cookie\CookieInterface
     */
    public static function cookie($name, $value = null, $expire = 0, $path = '/')
    {
        if (null === $value) {
            return static::request()->cookies->getCookie($name);
        }

        return static::request()->cookies->setCookie($name, $value, $expire, $path);
    }

    /**
     * @param $url
     * @param $statusCode
     * @param array $headers
     * @return \Dobee\Protocol\Http\RedirectResponse
     */
    public static function redirect($url, $statusCode, array $headers = array())
    {
        return new \Dobee\Protocol\Http\RedirectResponse($url, $statusCode, $headers);
    }

    /**
     * @param $connection
     * @param $repository
     * @return \Dobee\Database\Repository\Repository
     */
    public static function repository($connection, $repository)
    {
        return static::db($connection)->getRepository($repository);
    }

    /**
     * @param       $event
     * @param       $handle
     * @param array $parameters
     * @return array|string|\Dobee\Protocol\Http\Response
     */
    public static function event($event, $handle, array $parameters = array())
    {
        return static::app()->getContainer()->getProvider()->callServiceMethod($event, $handle, $parameters);
    }

    /**
     * @param       $template
     * @param array $parameters
     * @return string
     */
    public static function render($template, array $parameters = array())
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
        return static::app()->getContainer()->get($name, $parameters);
    }

    /**
     * @return \Kernel\AppKernel
     */
    public static function app()
    {
        return Application::create();
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
     * @return \Dobee\Protocol\Http\Request
     */
    public static function request()
    {
        return static::container('kernel.request');
    }

    /**
     * @param string $name
     * @param array $parameters
     * @return mixed
     */
    public static function helper($name, array $parameters = array())
    {
        return static::container($name, $parameters);
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
                $host = '//' . static::container('kernel.request')->getHost();
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
     * Application exception handle.
     *
     * @return void
     */
    public static function exception()
    {
        set_exception_handler(function (Exception $exception) {
            if (!Make::container('kernel')->getDebug()) {
                Make::log(sprintf('exception:[ code: %s, message: %s, file: %s, line: %s ]', $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine()));
            }

            if (false !== strpos(Make::request()->getPathInfo(), 'api')) {
                return (new \Dobee\Protocol\Http\JsonResponse(array('error' => $exception->getMessage()), $exception->getCode()))->send();
            }

            $error = <<<E
<html>
<head>
    <title>%s</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
        *{
            font-family:		Consolas, Courier New, Courier, monospace;
            font-size:			14px;
        }
        body {
            background-color:	#fff;
            margin:				40px;
            color:				#000;
        }

        #content  {
            border:				#999 1px solid;
            background-color:	#fff;
            padding:			20px 20px 12px 20px;
            line-height:26px;
        }

        h1 {
            font-weight:		normal;
            font-size:			24px;
            color:				#990000;
            margin: 			0 0 4px 0;
        }
        pre {
            width: auto;
            overflow-x: auto;
        }
    </style>
</head>
<body>
<div id="content">
    <h1>Error: %s</h1>
    <h2>File: %s</h2>
    <h2>Line: %s</h2>
    <hr />
    <pre>%s</pre>
</div>
</body>
</html>
E;
            $error = sprintf($error,
                $exception->getMessage(),
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine(),
                $exception->getTraceAsString()
            );

            $code = $exception->getCode();
            if (!Make::container('kernel')->getDebug()) {
                if ($code <= 0 || $code >= 500) {
                    $code = 500;
                }
                try {
                    $error = Make::render(Make::config('errors.' . $code), array('exception' => $exception));
                } catch(InvalidArgumentException $e){
                    $error = $exception->getMessage();
                }
            }

            return (new \Dobee\Protocol\Http\Response($error, $exception->getCode()))->send();
        });

        set_error_handler(function ($error_code, $error_str, $error_file, $error_line) {
            throw new \ErrorException($error_str, 500, 1, $error_file, $error_line);
        });

        register_shutdown_function(function () {
            $error = error_get_last();
            if ($error && in_array($error['type'], array(1, 4, 16, 64, 256, 4096, E_ALL))) {
                throw new \ErrorException($error['message'], $error['type'], 1, $error['file'], $error['line']);
            }
        });
    }

    /**
     * @var Make
     */
    protected static $make;

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array("Make::" . $method, $arguments);
    }

    /**
     * @return Make
     */
    public static function getMakeTool()
    {
        if (null === static::$make) {
            static::$make = new static();
        }

        return static::$make;
    }
}