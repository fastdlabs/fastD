<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Exceptions;

class BootstrapException extends AppException
{
    public function __construct($env, $file)
    {
        parent::__construct(sprintf('Application bootstrap environment "%s" exception. Bootstrap configuration file "%s" cannot open.', $env, $file));
    }
}
