<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Contract;

use FastD\App;

interface ServiceProviderInterface
{
    public function register(App $app);

    public function getName();
}