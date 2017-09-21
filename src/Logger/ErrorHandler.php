<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace FastD\Logger;

/**
 * Class ErrorHandler.
 */
class ErrorHandler extends HandlerAbstract
{
    protected function logContextFormat()
    {
        return [
            'ip' => get_local_ip(),
            'get' => request()->getQueryParams(),
            'post' => request()->getParsedBody(),
            'msg' => exception()->getMessage(),
            'code' => exception()->getCode(),
            'file' => exception()->getFile(),
            'line' => exception()->getLine(),
            'trace' => explode("\n", exception()->getTraceAsString()),
        ];
    }
}
