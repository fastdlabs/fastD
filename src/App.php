<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/11
 * Time: 下午3:57
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace FastD\Framework;

use FastD\Framework\Kernel\AppKernel;

/**
 * Class AppKernel
 *
 * @package FastD\Framework\Kernel
 */
class App extends AppKernel
{
    /**
     * Run framework into bootstrap file.
     *
     * @param $bootstrap
     * @return void
     */
    public static function run($bootstrap)
    {
        $app = new static($bootstrap);

        $app->bootstrap();

        $response = $app->createHttpRequestHandler();

        $response->send();

        $app->shutdown($app);
    }
}