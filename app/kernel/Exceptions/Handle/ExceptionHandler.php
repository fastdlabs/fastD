<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/8
 * Time: 下午10:47
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Kernel\Exceptions\Handle;

/**
 * Class ExceptionHandler
 *
 * @package Dobee\Framework
 */
class ExceptionHandler
{
    /**
     * @var AppKernel
     */
    private $application;

    /**
     * @param AppKernel $application
     */
    public function __construct(AppKernel $application)
    {
        $this->application = $application;
    }

    /**
     * @param \Exception $exception
     */
    public function handleException(\Exception $exception)
    {
        $wrapper = new ExceptionListenerWrapper($this->application, $exception);

        $response = $wrapper->getException();

        $response->send();

        $this->logException($exception);
    }

    /**
     * @param \Exception $exception
     */
    public function logException(\Exception $exception)
    {
        $logger = $this->application->getContainer()->get('kernel.logger');

        $content = 'error: [ code: %s, message: %s, line: %s, file: %s ]';

        $logger->setContent(sprintf($content,
            $exception->getCode(),
            $exception->getMessage(),
            $exception->getLine(),
            $exception->getFile()
        ));
    }
}