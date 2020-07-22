<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace FastD;


use Throwable;

/**
 * Class AppException
 * @package FastD
 */
class AppException implements Throwable
{
    protected Throwable $exception;

    public function __construct(Throwable $throwable)
    {
        $this->exception = $throwable;
    }

    public function getMessage()
    {
        return $this->exception->getMessage();
    }

    public function getCode()
    {
        return $this->exception->getCode();
    }

    public function getFile()
    {
        return $this->exception->getFile();
    }

    public function getLine()
    {
        return $this->exception->getLine();
    }

    public function getTrace()
    {
        return $this->exception->getTrace();
    }

    public function getTraceAsString()
    {
        return $this->exception->getTraceAsString();
    }

    public function getPrevious()
    {
        return $this->exception->getPrevious();
    }

    public function __toString()
    {
        return $this->exception->__toString();
    }
}
