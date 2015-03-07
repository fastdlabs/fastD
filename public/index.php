<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/2/7
 * Time: 上午1:57
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

$loader = include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../app/AppKernel.php';

$kernel = new AppKernel('dev', true);

$kernel->bootstrap(); // App bootstrap.

$kernel
    ->handlerGlobalRequest()
    ->send()
;

$kernel->terminate();

