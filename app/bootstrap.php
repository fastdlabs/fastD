<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/27
 * Time: 下午4:32
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

$composer = __DIR__ . '/../vendor/autoload.php';

if (!file_exists($composer)) {
    include __DIR__ . '/../dobee/Autoload/ClassLoader.php';
    $vendorDir = __DIR__ . '/../vendor';
    $loader = \Dobee\Autoload\ClassLoader::getLoader(array(
        ''          => array(__DIR__ . '/../src', __DIR__ . '/src'),
        'Plugins\\' => array(__DIR__ . '/plugins', __DIR__ . '/plugins'),
        'Dobee\\'   => array(__DIR__ . '/dobee', $vendorDir . '/dobee/container/src/Dobee', $vendorDir . '/dobee/template/src/Dobee', $vendorDir . '/dobee/annotation/src/Dobee', $vendorDir . '/dobee/configuration/src/Dobee', $vendorDir . '/dobee/finder/src/Dobee', $vendorDir . '/dobee/http/src/Dobee', $vendorDir . '/dobee/console/src/Dobee', $vendorDir . '/dobee/database/src/Dobee', $vendorDir . '/dobee/server/src/Dobee', $vendorDir . '/dobee/storage/src/Dobee', $vendorDir . '/dobee/routing/src/Dobee', $vendorDir . '/dobee/autoload/src/Dobee', $vendorDir . '/dobee/framework/src/Dobee'),
        'Monolog\\' => array(__DIR__ . '/dobee/monolog', $vendorDir . '/monolog/monolog/src/Monolog'),
        'Psr\\Log\\'=> array(__DIR__ . '/dobee/psr/log', $vendorDir . '/psr/log/Psr/Log'),
    ));
} else {
    $loader = include $composer;
}

include __DIR__ . '/Application.php';

return $loader;


