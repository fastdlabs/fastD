<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Process;

use FastD\Swoole\Process;

/**
 * Class AbstractProcess.
 */
abstract class AbstractProcess extends Process
{
    protected $options = [];

    /**
     * @param array $options
     *
     * @return $this
     */
    public function configure(array $options = [])
    {
        $this->options = $options;

        return $this;
    }
}
