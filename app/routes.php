<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/27
 * Time: 下午12:07
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */


/**
 * Global route setting
 *
 * Routes::get();
 * Routes::post()
 * Routes::put
 * Routes::delete
 * Routes::patch() // is unsupport
 * Routes::head
 * Routes::options
 */

Routes::get('/', function () {
    return 'hello FastD.';
});

