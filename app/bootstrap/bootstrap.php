<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/13
 * Time: 下午2:47
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

$loader = include __DIR__ . '/../../vendor/autoload.php';
include __DIR__ . '/../Application.php';

$app = new Application('dev');

return $app;
