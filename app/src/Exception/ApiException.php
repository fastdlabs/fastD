<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace Exception;

use FastD\Http\JsonResponse;

/**
 * Class ApiException
 * @package Exception
 */
class ApiException implements ExceptionInterface
{
    /**
     * @return string
     */
    public function format()
    {
        return JsonResponse::class;
    }

    public function handle(\Throwable $throwable)
    {
        // TODO: Implement handle() method.
    }
}
