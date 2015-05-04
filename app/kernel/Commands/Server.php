<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/16
 * Time: 下午5:12
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Framework\Bundle\Commands;

use Dobee\Config\Loader\YmlFileLoader;
use Dobee\Console\Commands\Command;
use Dobee\Console\Format\Input;
use Dobee\Console\Format\Output;
use Dobee\Framework\ExceptionHandler;
use Dobee\Framework\ExceptionListenerWrapper;
use Dobee\Http\Request;
use Dobee\Server\HttpServer;

class Server extends Command
{
    /**
     * @var \Dobee\Server\ServerInterface
     */
    protected $http;

    /**
     * @return string
     */
    public function getName()
    {
        return 'dobee:http';
    }

    /**
     * @return void|$this
     */
    public function configure()
    {
        $this->setDescription('Thank for you use Dobee Http Server');

        $this->setArguments('action');

        $this->initializeServer();

        $this->initializeHandlers();
    }

    public function initializeServer()
    {
        $app = $this->getProvider();

        $path = $app->getRootPath() . '/config/server.yml';

        $config = new YmlFileLoader($path);

        $config = $config->getParameters('server');

        if (!isset($config['http']['host']) || !isset($config['http']['port'])) {
            throw new \ErrorException(sprintf('Server start fail. Cannot server config "host" or "port".'));
        }

        $this->http = new HttpServer($config['http']['host'], $config['http']['port']);

        $this->http->setConfig(isset($config['http']['options']) ? $config['http']['options'] : array());
    }

    public function setProcessName($name)
    {
        if (function_exists('cli_set_process_title')) {
            cli_set_process_title($name);
        } else if(function_exists('swoole_set_process_name')) {
            swoole_set_process_name($name);
        }
    }

    public function start($server)
    {
        $this->setProcessName($this->http->getMasterName());
    }

    public function managerStart($server)
    {
        $this->setProcessName($this->http->getManagerName());
    }

    public function workerStart($server, $workerId)
    {
        $this->setProcessName($this->http->getWorkerName());
    }

    public function initializeHandlers()
    {
        $app = $this->getProvider();

        $debug = $app->getDebug();

        $this->http->setHandler('start', array($this, 'start'));
        $this->http->setHandler('workerStart', array($this, 'workerStart'));
        $this->http->setHandler('managerStart', array($this, 'managerStart'));

        $this->http->setHandler('request', function ($request, $response) use (&$httpRequest, &$app, $debug) {

            $httpRequest = Request::createGlobalRequest();

            $httpRequest->query->setParameters(isset($request->get) ? $request->get : array());
            $httpRequest->request->setParameters(isset($request->post) ? $request->post : array());
            $httpRequest->server->setParameters($request->server);

            try {
                $httpResponse = $app->handleHttpRequest($httpRequest);
            } catch (\Exception $e) {
                $handler = new ExceptionHandler($app);
                $handler->logException($e);
                $wrapper = new ExceptionListenerWrapper($app, $e);
                $httpResponse = $wrapper->getException();
                unset($wrapper, $handler);
            }

            $response->status($httpResponse->getStatusCode());

            foreach ($httpResponse->headers->all() as $name => $value) {
                $response->header($name, $value);
            }

            $response->gzip();

            $response->end($httpResponse->getContent());

            $app->terminate($httpRequest, $httpResponse);

            unset($httpRequest, $httpResponse);
        });
    }

    /**
     * @param Input  $input
     * @param Output $output
     * @return void
     */
    public function execute(Input $input, Output $output)
    {
        switch ($input->get('action')) {
            case 'start':
                $this->http->start();
                break;
        }
    }
}