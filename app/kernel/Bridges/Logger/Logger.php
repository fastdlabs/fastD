<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/11
 * Time: 下午4:31
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Framework\Bridges\Logger;

use Dobee\Config\Config;
use Dobee\Http\Request;
use Dobee\Http\Response;
use Monolog\Handler\StreamHandler;

/**
 * Class Logger
 *
 * @package Dobee\Framework\Bridges\Logger
 */
class Logger
{
    /**
     * @const string
     */
    const LOGGER_NAME = 'dobee.log';

    /**
     * @const string
     */
    const DEFAULT_PATH = './';

    /**
     * @const int
     */
    const LEVEL_ERROR = \Monolog\Logger::ERROR;

    /**
     * @const int
     */
    const LEVEL_INFO = \Monolog\Logger::INFO;

    /**
     * @var \Monolog\Logger
     */
    private $driver;

    /**
     * @var array
     */
    private $handlers = array();

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->driver = new \Monolog\Logger(Logger::LOGGER_NAME);

        try {
            $path = $config->getParameters('logger.path');
        } catch (\Exception $e) {
            $path = Logger::DEFAULT_PATH;
        }

        try {
            $name = $config->getParameters('logger.name');
        } catch (\Exception $e) {
            $name = Logger::LOGGER_NAME;
        }

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $this->driver->pushHandler(new StreamHandler($path . DIRECTORY_SEPARATOR . $name, \Monolog\Logger::INFO));
    }

    /**
     * @param $path
     * @return int
     */
    public function addLog($path)
    {
        $handler = new StreamHandler($path);

        $this->handlers[] = $handler;

        $this->driver->pushHandler($handler, \Monolog\Logger::INFO);

        return (count($this->handlers) - 1);
    }

    /**
     * @param $content
     */
    public function setContent($content)
    {
        $this->driver->addInfo($content);
    }

    /**
     * @param Request  $request
     * @param Response $response
     */
    public function logRequest(Request $request, Response $response)
    {
        $content = 'request: [ date: %s, path: %s, format: %s, method: %s, ip: %s } response: { date: %s, status: %s ]';

        $this->setContent(sprintf($content,
            date('Y-m-d H:i:s', $request->getRequestTimestamp()),
            $request->getPathInfo(),
            $request->getFormat(),
            $request->getMethod(),
            $request->getClientIp(),
            date('Y-m-d H:i:s', $response->getResponseTimestamp()),
            $response->getStatusCode()
        ));
    }
}