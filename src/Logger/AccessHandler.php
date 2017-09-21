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
 * Class AccessHandler.
 */
class AccessHandler extends HandlerAbstract
{
    /**
     * @return array
     */
    protected function logContextFormat()
    {
        return [
            'ip' => get_local_ip(),
            'status' => response()->getStatusCode(),
            'get' => request()->getQueryParams(),
            'post' => request()->getParsedBody(),
        ];
    }
}
