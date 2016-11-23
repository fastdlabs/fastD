<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

app()->route('/a', function (\FastD\Routing\RouteCollection $routeCollection) {
    $routeCollection->get('/', function () {
        return new \FastD\Http\Response('hello world! FastD');
    });
});
