<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
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
        return [];
    }
}
