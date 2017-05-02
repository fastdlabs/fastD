<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Logger;


use Monolog\Logger as MonoLogger;

/**
 * Class Logger
 * @package FastD\Logger
 */
class Logger extends MonoLogger
{
    /**
     * @param $levelCode
     * @return int
     */
    protected function convertStatusCodeToLevel($levelCode)
    {
        return ($levelCode >= 200 && $levelCode < 300) ? Logger::INFO : Logger::ERROR;
    }

    /**
     * @param int $level
     * @param string $message
     * @param array $context
     * @return bool
     */
    public function addRecord($level, $message, array $context = array())
    {
        return parent::addRecord($this->convertStatusCodeToLevel($level), $message, $context);
    }
}