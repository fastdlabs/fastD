<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/16
 * Time: 上午10:48
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

$composer = include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../app/Application.php';

$autoload = array_merge(
    include __DIR__ . '/../vendor/composer/autoload_psr4.php',
    array(
        'Psr\\Log\\' => array(__DIR__ . '/../vendor/psr/log/Psr/Log'),
    )
);

$loader = new \Dobee\Compiler\ClassCompiler($autoload);

$composer->unregister();

$loader->loader();

$app = new Application('dev');

$app->bootstrap();

try {
$app->handleHttpRequest();
} catch (Exception $e) {}

$loader->setNotIgnore(array(
    "Dobee\\"
));

$loader->setCompileFileName($app->getCacheName());

$loader->setCompilePath($app->getRootPath());
print_r($loader->getLoadClasses());
$loader->compileLoadClass();

$loader->saveCache();

