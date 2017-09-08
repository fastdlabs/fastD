<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Logger\Formatter;

use FastD\Application;
use Monolog\Formatter\LogstashFormatter;

/**
 * Class StashFormatter.
 */
class StashFormatter extends LogstashFormatter
{
    public function __construct()
    {
        parent::__construct(app()->getName(), get_local_ip(), null, 'fd_', Application::VERSION);
    }
}
