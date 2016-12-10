<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Bundle\Middleware;

use FastD\Middleware\ServerMiddleware;

class Auth extends ServerMiddleware
{
    public function __construct()
    {
        parent::__construct(function () {
            echo 1;
        });
    }
}