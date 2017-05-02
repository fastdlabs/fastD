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
use Monolog\Logger as MonoLogger;

/**
 * Class Logger.
 */
class Logger extends MonoLogger
{
    /**
     * @param $levelCode
     *
     * @return int
     */
    protected function convertStatusCodeToLevel($levelCode)
    {
        return ($levelCode >= 200 && $levelCode < 300) ? self::INFO : self::ERROR;
    }

    /**
     * @param int    $level
     * @param string $message
     * @param array  $context
     *
     * @return bool
     */
    public function addRecord($level, $message, array $context = array())
    {
        if (!$this->handlers) {
            $this->pushHandler(new StreamHandler('php://temp'));
        }

        return parent::addRecord($this->convertStatusCodeToLevel($level), $message, $context);
    }
}
