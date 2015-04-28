<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/9
 * Time: 上午12:24
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Framework;

namespace Kernel\Exceptions\Handle;

/**
 * Class ExceptionListenerWrapper
 *
 * @package Dobee\Framework
 */
class ExceptionListenerWrapper
{
    /**
     * @var \Exception
     */
    private $exception;

    /**
     * @var AppKernel
     */
    private $application;

    /**
     * @param AppKernel  $application
     * @param \Exception $exception
     */
    public function __construct(AppKernel $application, \Exception $exception)
    {
        $this->exception = $exception;

        $this->application = $application;
    }

    /**
     * @return Response
     */
    public function getException()
    {
        return new Response($this->getExceptionContent(), $this->exception->getCode());
    }

    /**
     * @return string
     */
    public function getExceptionContent()
    {
        if ($this->application->getDebug()) {
            return sprintf($this->getDefault(),
                $this->exception->getMessage(),
                $this->exception->getMessage(),
                $this->exception->getTraceAsString()
            );
        }

        try {
            $page = $this->getCustomContent();
        } catch (\InvalidArgumentException $e) {
            $page = null;
        }

        return $this->renderBody($page);
    }

    /**
     * @param null|string $template
     * @return string
     */
    public function renderBody($template = null)
    {
        if (null === $template) {
            return sprintf($this->getProdContent(),
                $this->exception->getMessage(),
                $this->exception->getMessage(),
                $this->exception->getTraceAsString()
            );
        }

        $engine = $this->application->getContainer()->get('kernel.template', array($this->application->getContainer()))->getEngine();

        return $engine->render($template, array(
            'exception' => $this->exception,
            'message'   => $this->exception->getMessage(),
            'trace'     => $this->exception->getTraceAsString()
        ));
    }

    /**
     * @return string
     */
    public function getProdContent()
    {
        return '<html>
<head>
    <title>服务器可能病了</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">

    </style>
</head>
<body>
<div id="content">
    <h1>服务器可能病了！</h1>
    <h2>正在医院打点滴。。。放心，没有生命危险</h2>
    <h3>错误代码：500</h3>
</div>
</body>
</html>';
    }

    /**
     * @return string
     */
    public function getDefault()
    {
        return '<html>
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
</html>';
    }

    /**
     * @return null|string
     */
    public function getCustomContent()
    {
        $container = $this->application->getContainer();
        $errors = $container->get('kernel.config')->getParameters('errors.page');

        $code = $this->exception->getCode() < 0 ? 500 : $this->exception->getCode();

        $page = isset($errors[$code]) ? $errors[$code] : null;

        return $page;
    }
}