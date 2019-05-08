<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2019
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace FastD\Exception;


use FastD\Http\Response;
use Throwable;

/**
 * Interface ExceptionHandlerInterface
 * @package Exception
 */
interface ExceptionHandlerInterface
{
    /**
     * ExceptionHandlerInterface constructor.
     * @param array $options
     */
    public function __construct(array $options);

    /**
     * @param Throwable $throwable
     * @return mixed
     */
    public function handle(Throwable $throwable): Response;
}
