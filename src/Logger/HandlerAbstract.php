<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Logger;

use Monolog\Handler\StreamHandler;

abstract class HandlerAbstract extends StreamHandler
{
    /**
     * Writes the record down to the log of the implementing handler.
     *
     * @param array $record
     */
    protected function write(array $record = [])
    {
        $record['context'] = $this->logContextFormat();

        parent::write($record);
    }

    /**
     * @return array
     */
    abstract protected function logContextFormat();
}
