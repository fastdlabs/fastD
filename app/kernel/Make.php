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
     * @param $url
     * @param $statusCode
     * @return \Dobee\Http\RedirectResponse
     */
    public static function redirect($url, $statusCode)
    {
        return new \Dobee\Http\RedirectResponse($url, $statusCode);
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
     * @return array|string|\Dobee\Http\Response
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
     * @return \Dobee\Http\Request
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
                return (new \Dobee\Http\JsonResponse(array('error' => $exception->getMessage()), $exception->getCode()))->send();
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
    <pre>%s</pre>
</div>
</body>
</html>
E;
            $error = sprintf($error,
                $exception->getMessage(),
                $exception->getMessage(),
                $exception->getTraceAsString()
            );

            if (!Make::container('kernel')->getDebug()) {
                try {
                    $error = Make::render(Make::config('errors.' . $exception->getCode()), array('exception' => $exception));
                } catch(Exception $e){
                    $error = $exception->getMessage();
                }
            }

            return (new \Dobee\Http\Response($error, $exception->getCode()))->send();
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
}