<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Contract;

class StoreContract
{
    protected $app;

    public function __construct()
    {
        $this->app = app();
    }
}